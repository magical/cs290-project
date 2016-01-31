<?php

require_once "includes/all.php";

// Connect to the database

$db = connect_db();

// Insert rows from catalog.csv

$fp = fopen("catalog.csv", "r");
if ($fp === false) {
  die("couldn't open catalog.csv");
}

$header = fgetcsv($fp);
if ($header !== array("dept", "num", "title", "term")) {
  die("Catalog.csv is not in the correct format");
}

$db->beginTransaction();
try {
  //$db->exec('DELETE FROM courses where id > 2');
  $stmt = $db->prepare("INSERT INTO courses (department, number, year, title) VALUES (:department, :number, :year, :title)");

  while ($row = fgetcsv($fp)) {
    // ugh
    $row['dept'] = $row[0];
    $row['num'] = $row[1];
    $row['title'] = $row[2];
    $row['term'] = $row[3];

    // ignore honors courses
    if (substr($row['num'], -1) == 'H') {
      continue;
    }

    // just import this term for now
    if ($row['term'] === "Winter 2016") {
      $stmt->bindValue("department", $row['dept']);
      $stmt->bindValue('number', $row['num']);
      $stmt->bindValue('title', $row['title']);
      $stmt->bindValue('year', 2016);
      $stmt->execute();
    }
  }
  $db->commit();
} catch (Exception $e) {
  $db->rollBack();
  throw $e;
}

fclose($fp);

// Display values

$db = null;

include 'display.php';

