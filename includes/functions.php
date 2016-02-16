<?php

// functions.php - common functionality

// Reports whether a user is currently logged in.
function is_logged_in() {
  return array_key_exists('user_id', $_SESSION);
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

function get_user($db, $id) {
  $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
  $stmt->bindValue("id", $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
}

function get_user_courses($db, $user_id) {
  $stmt = $db->prepare('
    SELECT courses.*
    FROM courses
    JOIN user_courses ON user_courses.course_id = courses.id
    WHERE user_id = :user_id');
  $stmt->bindValue("user_id", $user_id);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

// Returns the full URL of the current page (without query parameters)
function current_url() {
  $url = 'http';
  if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $url .= "s";
  }
  $url .= "://";
  if ($_SERVER["SERVER_PORT"] != "80") {
    $url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
  } else {
    $url .= $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
  }

  return $url;
}
