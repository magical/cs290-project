<?php

require_once "includes/all.php";

// Connect to the database

$db = connect_db();

// Insert values

$stmt = $db->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");

$stmt->bindValue("email", $_POST["email"]);
$stmt->bindValue("password_hash", password_hash($_POST["password"], PASSWORD_BCRYPT));
$stmt->execute();

header("Location: signin.php");
exit();
