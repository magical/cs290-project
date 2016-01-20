<?php

include "config.php";

// Connect to the database

try {
    // TODO(ae): persistent?
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database";
    die();
}

// Create tables

$db->exec('DROP TABLE IF EXISTS users');
$db->exec('
CREATE TABLE users (
    id INTEGER,
    email VARCHAR(255),
    name VARCHAR(255),
    -- major_id INTEGER,
    -- standing_id INTEGER,

    PRIMARY KEY (id)
    -- FOREIGN KEY (major_id) REFERENCES majors (id),
    -- FOREIGN KEY (standing_id) REFERENCES class_standings (id),
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('DROP TABLE IF EXISTS classes');
$db->exec('
CREATE TABLE classes (
    id INTEGER,
    department VARCHAR(255), -- eg MTH
    course VARCHAR(255), -- eg MTH252
    year INTEGER, -- eg 2016
    title VARCHAR(255), -- eg Integral Calculus

    PRIMARY KEY(id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('DROP TABLE IF EXISTS groups');
$db->exec('
CREATE TABLE groups (
    id INTEGER,

    PRIMARY KEY(id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

// Insert values

$stmt = $db->prepare("INSERT INTO users VALUES (:id, :email, :name)");

$stmt->bindValue("id", 1);
$stmt->bindValue("email", "bob@oregonstate.edu");
$stmt->bindValue("name", "Bob '; DROP TABLE STUDENTS; --");
$stmt->execute();

$stmt->bindValue("id", 2);
$stmt->bindValue("email", "ekstedta@oregonstate.edu");
$stmt->bindValue("name", "Andrew Ekstedt");
$stmt->execute();

// Display values

$stmt = $db->prepare("SELECT * FROM users");
$stmt->execute();
echo "<!doctype html>\n";
echo '<table border=1>'."\n";
echo '  <tr><th>id<th>name<th>email</tr>'."\n";
foreach ($stmt as $row) {
    echo '  <tr>';
    echo '<td>'.htmlspecialchars($row['id']).'</td>';
    echo '<td>'.htmlspecialchars($row['name']).'</td>';
    echo '<td>'.htmlspecialchars($row['email']).'</td>';
    echo '</tr>'."\n";
}
echo '</table>';

?>
