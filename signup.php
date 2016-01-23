<?php
session_start();
if (array_key_exists("isLoggedIn", $_SESSION) && $_SESSION["isLoggedIn"] == 1) {
	header("Location: index.php");
	exit();
} else {
	include 'includes/_signup.html';
}
?>
