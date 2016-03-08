<?php
// pic_display.php displays the picture for the given *user* id

require_once 'includes/all.php';

$db = connect_db();
if (array_key_exists('id', $_GET)) {
	$user_id = $_GET['id'];
} else {
	header('Status: 404');
	die('no pic id');
}
$stmt = $db->prepare("
  SELECT filedata, mimetype
  FROM pic JOIN users ON users.pic_id = pic.id
  WHERE users.id = :user_id");
$stmt->bindParam("user_id", $user_id);
$stmt->execute();
$profpic = $stmt->fetch();
if ($profpic === false) {
	header('Status: 404');
	die('no such pic');
}
header("Content-Type: ".$profpic['mimetype']);
echo $profpic['filedata'];
