<?php
require_once 'includes/all.php';

$group_id = null;

if (!isset($_GET['q'])) {
  header ('Content-Type: application/json');
  echo '[]';
  exit(0);
}

// Escape query and append '%' to match a prefix.
$query = $_GET['q'];
$query = preg_replace("/([_%^])/", '^$1', $query);
$query .= "%";

$from = "users";
$where = "users.name LIKE :query_1 ESCAPE '^' OR users.email LIKE :query_2 ESCAPE '^'";

// If group_id specified, limit users to members of the given group
$params = array();
if (isset($_GET['group_id']) && preg_match('/^[0-9]+$/', $_GET['group_id'])) {
  $where = "($where) AND EXISTS (SELECT 1 FROM group_members WHERE users.id = group_members.user_id AND group_members.group_id = :group_id)";
  $params[':group_id'] = (int)$_GET['group_id'];
}

$db = connect_db();

$q = $db->prepare("SELECT users.id id, users.name n, users.email e FROM users WHERE $where ORDER BY users.name LIMIT 10");
$q->bindValue(":query_1", $query);
$q->bindValue(":query_2", $query);
foreach ($params as $key => $value) {
  $q->bindValue($key, $value);
}
$q->execute();
$results = $q->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($results);
