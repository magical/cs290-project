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

$stmt = $db->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");

$stmt->bindValue("email", $_POST["email"]);
$stmt->bindValue("password_hash", password_hash($_POST["password"], PASSWORD_BCRYPT));
$stmt->execute();

header("Location: signin.php");
exit();
?>
