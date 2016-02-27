<?php

// functions.php - common functionality

// Reports whether a user is currently logged in.
function is_logged_in() {
  return array_key_exists('user_id', $_SESSION);
}

// Returns the id of the currently logged in user,
// or 0 if no user is logged in.
function get_logged_in_user_id() {
  if (is_logged_in()) {
    return $_SESSION['user_id'];
  }
  return 0;
}

// Connect to the database and return a new PDO object.
// If connection is unsuccessful, prints an error and terminates the page.
function connect_db() {
  global $dsn, $dbuser, $dbpass;
  try {
    // TODO(ae): persistent?
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  } catch (PDOException $e) {
    echo "Error connecting to database";
    exit();
  }
  return $db;
}

// Accessors

// Gets a user from the database by id.
function get_user($db, $id) {
  $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
  $stmt->bindValue(":id", $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
}

// Gets a list of courses from the database by user id.
function get_user_courses($db, $user_id) {
  $stmt = $db->prepare('
    SELECT courses.*
    FROM courses
    JOIN user_courses ON user_courses.course_id = courses.id
    WHERE user_id = :user_id');
  $stmt->bindValue(":user_id", $user_id);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

// Gets a group from the database by id.
function get_group($db, $id) {
  $stmt = $db->prepare('SELECT * FROM groups WHERE id = :id');
  $stmt->bindValue(":id", $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
}

// Gets a list of users from the database by group id.
function get_group_members($db, $group_id) {
  $stmt = $db->prepare('
    SELECT users.*
    FROM users
    JOIN group_members ON group_members.user_id = users.id
    WHERE group_id = :group_id');
  $stmt->bindValue(":group_id", $group_id);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

// Gets a list of discussion posts by group id.
function get_group_posts($db, $group_id) {
  $stmt = $db->prepare('
    SELECT group_posts.*, users.name as user_name
    FROM group_posts
    JOIN users ON users.id = group_posts.user_id
    WHERE group_id = :group_id
    ORDER BY created_at DESC');
  $stmt->bindValue(":group_id", $group_id);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

// Gets a course from the database by id.
function get_course($db, $id) {
  $stmt = $db->prepare('SELECT * FROM courses WHERE id = :id');
  $stmt->bindValue(":id", $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
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
