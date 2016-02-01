<?php

// all.php - includes all the important include files

// figure out where the root of the project is
// (our parent directory)
$root = dirname(dirname(__FILE__));

// include library files
require_once $root.'/config.php';
require_once $root.'/includes/functions.php';

// start the session
session_start();
