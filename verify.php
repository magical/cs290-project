<?php
session_start();
require_once "includes/all.php";

$db = connect_db();

$stmt = $db->prepare("SELECT password_hash FROM users WHERE email=:email");
$stmt->bindValue("email", $_POST["email"]);
$stmt->execute();

foreach ($stmt as $row) {
	if (password_verify($_POST["password"], $row["password_hash"])) {
		$_SESSION["isLoggedIn"] = 1;
	} else {
		$_SESSION["isLoggedIn"] = 0;
	}
}

header("Location: index.php");
exit();
