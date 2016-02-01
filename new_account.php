<?php

if ($_POST["password"] == $_POST["passwordConfirm"]) {
	require_once "includes/all.php";

	// Connect to the database

	$db = connect_db();

	// Retrieve id

	$stmt = $db->prepare("SELECT id FROM users WHERE email=:email");

	$stmt->bindValue("email", $_POST["email"]);

	if (!$stmt->fetch()) {

		// Insert values

		$stmt = $db->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");

		$stmt->bindValue("email", $_POST["email"]);
		$stmt->bindValue("password_hash", password_hash($_POST["password"], PASSWORD_BCRYPT));
		$stmt->execute();
	}
}

header("Location: signin.php");
exit();
