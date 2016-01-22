<?php
session_start();
if ($_POST["email"] == "user@host.tld" && $_POST["password"] == "pass") {
    $_SESSION["isLoggedIn"] = 1;
} else {
	$_SESSION["isLoggedIn"] = 0;
}
header("Location: index.php");
exit();
?>
