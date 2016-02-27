<?php

require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("Location: index.php");
  exit(0);
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

// can only post to groups your are a member of the group
if (!user_is_in_group($db, $user, $group['id'])) {
  die("you are not in that group");
}

$body = trim($_POST['body']);
if($body === "") {
  // no body??? let's send you back to the group page
  $url = "group.php";
  $url .= "?id=".urlencode($group['id']);
  header("Location: ".$url);
  exit(0);
}

$stmt = $db->prepare("INSERT INTO group_posts (group_id, user_id, body) VALUES (:group_id, :user_id, :body)");
$stmt->bindValue(":group_id", $group['id'], PDO::PARAM_INT);
$stmt->bindValue(":user_id", $user['id'], PDO::PARAM_INT);
$stmt->bindValue(":body", $body);
$stmt->execute();

$post_id = $db->lastInsertId();

$url = "group.php";
$url .= "?id=".urlencode($group['id']);
$url .= "#post-".urlencode($post_id);
header("Location: ".$url);
