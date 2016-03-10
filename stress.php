<?php

// How many users to create
$NUMBER_OF_USERS = 2000;
// How many classes to give each user
$NUMBER_OF_CLASSES = 4;

// Number of groups to create
$NUMBER_OF_GROUPS = 1000;
// Number of members to add to each group
$NUMBER_OF_MEMBERS_MIN = 2;
$NUMBER_OF_MEMBERS_MAX = 6;


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

// Returns a random weekday.
function random_day() {
  global $week_names;
  return $week_names[mt_rand(0, 6)];
}

// Returns a time between 8am and midnight.
function random_time() {
  return mt_rand(8, 23);
}

function random_place() {
  return "Earth"; // sue me
}

header("Content-Type: text/plain");

mt_srand(1); // determinism

require_once "config.php";
require_once "includes/functions.php";
require_once "includes/times.php";
$db = connect_db();



// Get largest id of some tables to assist random generation
$q = $db->query("SELECT max(id) FROM campuses"); $max_campus_id = $q->fetch()[0];
$q = $db->query("SELECT max(id) FROM colleges"); $max_college_id = $q->fetch()[0];
$q = $db->query("SELECT max(id) FROM standings"); $max_standing_id  = $q->fetch()[0];
$q = $db->query("SELECT max(id) FROM courses"); $max_course_id  = $q->fetch()[0];

// Remove old random rows
$db->query("DELETE FROM user_courses WHERE user_id > 6");
$db->query("DELETE FROM group_members WHERE user_id > 6");
$db->query("DELETE FROM groups WHERE id > 3");
$db->query("DELETE FROM users WHERE id > 6");

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
$q = $db->prepare("UPDATE users SET day1 = :day1, time1 = :time1, day2 = :day2, time2 = :time2 WHERE id = :user_id");
foreach ($userids as $userid) {
  $day1 = random_day();
  $time1 = random_time();
  $day2 = random_day();
  $time2 = random_time();
  while ($time2 === $time1 && $day2 === $day1) {
    $day2 = random_day();
    $time2 = random_time();
  }
  $q->bindValue(":day1", $day1);
  $q->bindValue(":time1", $time1);
  $q->bindValue(":day2", $day2);
  $q->bindValue(":time2", $time2);
  $q->bindValue(":user_id", $userid);
  $q->execute();
}

$groupquery = $db->prepare("INSERT INTO groups (course_id, name, day, time, place) VALUES (:course_id, :name, :day, :time, :place)");
$memberquery = $db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)");
$classquery = $db->prepare("INSERT INTO user_courses (course_id, user_id) VALUES (:course_id, :user_id)");

function is_taking($user_id, $course_id) {
  global $db;
  $q = $db->prepare("SELECT EXISTS (SELECT 1 FROM user_courses WHERE course_id = :course_id AND user_id = :user_id)");
  $q->bindValue(":course_id", $course_id);
  $q->bindValue(":user_id", $user_id);
  $q->execute();
  return !!$q->fetch()[0] ;
}

for ($j = 0; $j < $NUMBER_OF_GROUPS; $j++) {
  // Pick a random course
  $course_id = mt_rand(1, $max_course_id);
  $course = get_course($db, $course_id);

  $name = "Random ${course['title']} group";
  $day = random_day();
  $time = random_time();
  $place = random_place();

  $groupquery->bindValue(":course_id", $course_id);
  $groupquery->bindValue(":name", $name);
  $groupquery->bindValue(":day", $day);
  $groupquery->bindValue(":time", $time);
  $groupquery->bindValue(":place", $place);
  $groupquery->execute();
  $group_id = $db->lastInsertId();

  // Add members
  $nmembers = mt_rand($NUMBER_OF_MEMBERS_MIN, $NUMBER_OF_MEMBERS_MAX);
  $useridids = random_select($nmembers, 0, count($userids)-1);
  foreach ($useridids as $i) {
    if (!is_taking($userids[$i], $course_id)) {
      $classquery->bindValue(":course_id", $course_id);
      $classquery->bindValue(":user_id", $userids[$i]);
      $classquery->execute();
    }
    $memberquery->bindValue(":group_id", $group_id);
    $memberquery->bindValue(":user_id", $userids[$i]);
    $memberquery->execute();
  }
}

echo "Done";
