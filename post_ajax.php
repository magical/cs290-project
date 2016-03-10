<?php

require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Status: 403");
  exit();
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("Status: 405"); // method not allowed
  exit();
}


$db = connect_db();
$group = get_group($db, $_POST['group_id']);
$course = get_course($db, $group['course_id']);
$user = get_user($db, get_logged_in_user_id());

function user_is_in_group($db, $user, $group_id) {
  $stmt = $db->prepare("SELECT EXISTS (SELECT 1 FROM group_members WHERE group_id = :group_id AND user_id = :user_id)");
  $stmt->bindValue(":user_id", $user['id']);
  $stmt->bindValue(":group_id", $group_id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_NUM);
  return !!$row[0];
}

function json_die($status, $msg) {
  header("Status: 400");
  header("Content-Type: application/json");
  echo json_encode(array("error" => $msg));
  exit();
}

// can only post to groups your are a member of the group
if (!user_is_in_group($db, $user, $group['id'])) {
  header("Status: 403");
  die(json_encode(array("error"=>"you are not in this group")));
}

$body = trim($_POST['body']);
if($body === "") {
  json_die(400, "empty post");
}

$stmt = $db->prepare("INSERT INTO group_posts (group_id, user_id, body) VALUES (:group_id, :user_id, :body)");
$stmt->bindValue(":group_id", $group['id'], PDO::PARAM_INT);
$stmt->bindValue(":user_id", $user['id'], PDO::PARAM_INT);
$stmt->bindValue(":body", $body);
$stmt->execute();

$post_id = $db->lastInsertId();

// If successful, return the rendered post

$stmt = $db->prepare("SELECT body, created_at FROM group_posts WHERE id = :post_id");
$stmt->bindValue(":post_id", $post_id);
$stmt->execute();

$post = $stmt->fetch();
if ($post === false) {
  // shouldn't happen
  json_die(500, "couldn't fetch post");
}

$date = new DateTime($post['created_at']);
$body = "";
$body .= '<article id="post-'.$post_id.'">';
$body .= '<b>'.htmlspecialchars($user['name']);
$body .= ' on '.htmlspecialchars($date->format("M j"));
$body .= ' at '.htmlspecialchars($date->format("H:i"));
$body .= '</b>';
$body .= '<p>'.htmlspecialchars($post['body']).'</p>';
$body .= '</article>';

echo json_encode(array("id" => $post_id, "post" => $body));
