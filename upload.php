<?php
require_once 'includes/all.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('Status: 405'); // 405 Method Not Allowed
}

$db=connect_db();
$user_id = get_logged_in_user_id();

$pic=$_FILES["fileupload"]["name"];
$tmp=$_FILES["fileupload"]["tmp_name"];
$size=$_FILES["fileupload"]["size"];

$errors = array();
$maxsize = 1048756;
if ($size > $maxsize) {
	$errors[] = "Upload failed: file size $size exceeds maximum size of $maxsize";
}

$info = getimagesize($tmp);
if ($info === false) {
	$errors[] = "Upload failed: not an image";
} else {
	$type = $info[2];
	if (!($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF)) {
		$errors[] = "Upload failed: not an allowed image type";
	}
}

if (count($errors)) {
	$_SESSION['flash_errors'] = $errors;
	header("Location: profile_edit.php");
	exit(0);
}

$filedata=file_get_contents($tmp);

$stmt = $db->prepare("SELECT pic_id FROM users WHERE id = :user_id");
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();

$old_pic_id = $stmt->fetch()[0];

$stmt=$db->prepare("INSERT INTO pic (filename, filedata, filesize, mimetype) VALUES (?,?,?,?)");
$stmt->bindValue(1, $pic, PDO::PARAM_STR);
$stmt->bindValue(2, $filedata, PDO::PARAM_LOB);
$stmt->bindValue(3, $size, PDO::PARAM_INT);
$stmt->bindValue(4, $info['mime']);
$stmt->execute();

$pic_id = $db->lastInsertId();

$stmt=$db->prepare("UPDATE users SET pic_id = :pic_id WHERE id = :user_id");
$stmt->bindValue(":pic_id", $pic_id);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();

if ($old_pic_id) {
	$stmt = $db->prepare("DELETE FROM pic WHERE id = :pic_id");
	$stmt->bindValue(":pic_id", $old_pic_id, PDO::PARAM_INT);
	$stmt->execute();
}

$_SESSION['flash_success'] = array('Upload Successful');
header("Location: profile_edit.php");
