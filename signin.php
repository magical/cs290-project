<?php
session_start();
require_once 'includes/all.php';
if (is_logged_in()) {
	header("Location: index.php");
	exit();
} else {
	include 'includes/_signin.html';
}
