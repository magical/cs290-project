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

//TODO: Protect against SQL injection
$stmt = $db->prepare("INSERT INTO accounts (email, pass_hash) VALUES (:email, :pass_hash)");

$stmt->bindValue("email", $_POST["email"]);
$stmt->bindValue("pass_hash", password_hash($_POST["password"], PASSWORD_BCRYPT));
$stmt->execute();

header("Location: signin.php");
exit();
?>
