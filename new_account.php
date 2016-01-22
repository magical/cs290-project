<?php

require_once "config.php";

// Connect to the database

try {
	$db = new PDO($dsn, $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "Error connecting to database";
	die();
}

// Insert values

$stmt = $db->prepare("INSERT INTO accounts (email, username, pass_hash) VALUES (:email, :username, :pass_hash)");

$stmt->bindValue("email", "user@host.tld");
$stmt->bindValue("username", "user");
$stmt->bindValue("pass_hash", password_hash("pass", PASSWORD_BCRYPT));
$stmt->execute();

header("Location: index.php");
exit();
?>
