<?php

// all.php - includes all the important include files

// enable error reporting
// TODO(ae): disable in production
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

// figure out where the root of the project is
// (our parent directory)
$root = dirname(dirname(__FILE__));

// set the session save path
session_save_path($root . "/sessions");

// include library files
require_once $root.'/config.php';
require_once $root.'/includes/functions.php';
require_once $root.'/includes/times.php';

// start the session
session_start();
