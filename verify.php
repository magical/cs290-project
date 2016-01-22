<?php
session_start();

require_once "config.php";

// Connect to the database

try {
	$db = new PDO($dsn, $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "Error connecting to database";
	die();
}

//TODO: Protect against SQL injection
$stmt = $db->prepare("SELECT * FROM accounts WHERE email='".$_POST["email"]."'");
$stmt->execute();

foreach ($stmt as $row) {
	if (password_verify($_POST["password"], $row["pass_hash"])) {
		$_SESSION["isLoggedIn"] = 1;
	} else {
		$_SESSION["isLoggedIn"] = 0;
	}
}

header("Location: index.php");
exit();
?>
