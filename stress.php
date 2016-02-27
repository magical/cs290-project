<?php

// How many users to create
$NUMBER_OF_USERS = 2000;
// How many classes to give each user
$NUMBER_OF_CLASSES = 4;


// Load first and last names from files
$firstnames = array();
$lastnames = array();

$f = fopen("firstnames.txt", "r");
if ($f == NULL) {
  die("error opening firstnames.txt");
}
for($line = fgets($f); $line !== false; $line = fgets($f)) {
  $firstnames[] = ucfirst(strtolower(trim($line)));
}
fclose($f);

$f = fopen("lastnames.txt", "r");
if ($f == NULL) {
  die("error opening lastnames.txt");
}
for($line = fgets($f); $line !== false; $line = fgets($f)) {
  $lastnames[] = ucfirst(strtolower(trim($line)));
}
fclose($f);

// Returns a random first name and last name.
function random_name() {
  global $firstnames;
  global $lastnames;
  $j = mt_rand(0, count($firstnames)-1);
  $k = mt_rand(0, count($lastnames)-1);
  return $firstnames[$j].' '.$lastnames[$k];
}

// Returns $k random non-repeated values between $min and $max.
// To avoid pathological behaviour, $k should much smaller than $max-$min.
function random_select($k, $min, $max) {
  $values = array();
  while (count($values) < $k) {
    $n = mt_rand($min, $max);
    if (!in_array($n, $values)) {
      $values[] = $n;
    }
  }
  return $values;
}

// Returns a random day and time between 8am and midnight.
function random_time() {
  // Bizarre time format: dtttt
  // where d    is a digit for the day: 1 being Monday and 7 being Sunday
  //       tttt is the 4-digit 24-hour time
  $d = mt_rand(1, 7);
  $t = mt_rand(8, 23);
  return sprintf("%01d%02d00", $d, $t);
}

header("content-type: text/plain");

mt_srand(1); // determinism

require_once "config.php";
require_once "includes/functions.php";
$db = connect_db();

// Get largest id of some tables to assist random generation
$q = $db->query("SELECT max(id) FROM campuses"); $max_campus_id = $q->fetch()[0];
$q = $db->query("SELECT max(id) FROM colleges"); $max_college_id = $q->fetch()[0];
$q = $db->query("SELECT max(id) FROM standings"); $max_standing_id  = $q->fetch()[0];
$q = $db->query("SELECT max(id) FROM courses"); $max_course_id  = $q->fetch()[0];

// Remove old random rows
$db->query("DELETE FROM user_courses WHERE user_id > 8");
$db->query("DELETE FROM group_members WHERE user_id > 8");
$db->query("DELETE FROM users WHERE id > 8");

// Create N random users
$userids = array();
$q = $db->prepare("INSERT INTO users (name, email, phone, campus_id, college_id, standing_id) VALUES (:name, :email, :phone, :campus_id, :college_id, :standing_id)");
for ($i = 0; $i < $NUMBER_OF_USERS; $i++) {
  $name = random_name();
  $email = "user$i@example.com";
  $phone = sprintf("800555%04d", $i);
  $campus_id = mt_rand(1, $max_campus_id);
  $college_id = mt_rand(1, $max_college_id);
  $standing_id = mt_rand(1, $max_standing_id);

  //echo $name, "\n";
  //echo $email, "\n";
  //echo $phone, "\n";
  //echo $campus_id, "\n";
  //echo $college_id, "\n";
  //echo $standing_id, "\n";

  $q->bindValue(":name", $name);
  $q->bindValue(":email", $email);
  $q->bindValue(":phone", $phone);
  $q->bindValue(":campus_id", $campus_id);
  $q->bindValue(":college_id", $college_id);
  $q->bindValue(":standing_id", $standing_id);
  $q->execute();

  $userids[] = $db->lastInsertId();
}

// Give each user N random classes
$q = $db->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (:user_id, :course_id)");
foreach ($userids as $userid) {
  // Choose between 1 and $NUMBER_OF_CLASSES classes.
  $nclasses = mt_rand(1, $NUMBER_OF_CLASSES);
  $courseids = random_select($nclasses, 1, $max_course_id);
  foreach ($courseids as $course_id) {
    $q->bindValue(":user_id", $userid);
    $q->bindValue(":course_id", $course_id);
    $q->execute();
  }
}

// Pick two random times between 8am and 12pm (any day)
$q = $db->prepare("UPDATE users SET time1 = :time1, time2 = :time2 WHERE id = :user_id");
foreach ($userids as $userid) {
  $time1 = random_time();
  $time2 = random_time();
  while ($time2 === $time1) {
    $time2 = random_time();
  }
  $q->bindValue(":time1", $time1);
  $q->bindValue(":time2", $time2);
  $q->bindValue(":user_id", $userid);
  $q->execute();
}

echo "Done";
