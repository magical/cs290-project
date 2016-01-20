<?php

include "config.php";

try {
    // TODO(ae): persistent?
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database";
    die();
}

$db->exec("drop table students");
$db->exec("
  create table students (
      id integer,
      email varchar(255),
      name varchar(255),
      PRIMARY KEY(id)
  ) ENGINE=InnoDB, CHARACTER SET=utf8;
");

$stmt = $db->prepare("insert into students values (:id, :email, :name)");
$stmt->bindValue("id", 1);
$stmt->bindValue("email", "bob@oregonstate.edu");
$stmt->bindValue("name", "Bob '; DROP TABLE STUDENTS; --");
$stmt->execute();

$stmt = $db->prepare("select * from students");
$stmt->execute();
echo '<table border=1>';
foreach ($stmt as $row) {
  echo '<tr><td>';
  echo htmlspecialchars($row['name']);
  echo '</td></tr>';
}
echo '</table>';
//$stmt = $db->prepare("


?>
