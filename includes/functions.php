<?php

// functions.php - common functionality

// Reports whether a user is currently logged in.
function is_logged_in() {
  return array_key_exists('isLoggedIn', $_SESSION) && $_SESSION['isLoggedIn'] == 1;
}

// Connect to the database and return a new PDO object.
// If connection is unsuccessful, prints an error and terminates the page.
function connect_db() {
  global $dsn, $dbuser, $dbpass;
  try {
    // TODO(ae): persistent?
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo "Error connecting to database";
    exit();
  }
  return $db;
}
