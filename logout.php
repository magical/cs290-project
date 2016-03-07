<?php
require_once 'includes/all.php';
session_destroy();
header("Location: index.php");
exit();
