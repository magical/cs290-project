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

// Drop tables

$db->exec('DROP TABLE IF EXISTS group_members');
$db->exec('DROP TABLE IF EXISTS groups');
$db->exec('DROP TABLE IF EXISTS courses');
$db->exec('DROP TABLE IF EXISTS users');

// Create tables

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

$db->exec('
CREATE TABLE courses (
    id INTEGER,
    department VARCHAR(255), -- eg MTH
    course VARCHAR(255), -- eg MTH252
    year INTEGER, -- eg 2016
    title VARCHAR(255), -- eg Integral Calculus

    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE groups (
    id INTEGER,
    course_id INTEGER,

    PRIMARY KEY (id),
    FOREIGN KEY (course_id) REFERENCES courses (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE group_members (
    group_id INTEGER,
    user_id INTEGER,

    PRIMARY KEY (group_id, user_id),
    FOREIGN KEY (group_id) REFERENCES groups (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
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

$stmt = $db->prepare("INSERT INTO courses (id, department, course, year, title) VALUES (:id, :department, :course, :year, :title)");

$stmt->bindValue("id", 1);
$stmt->bindValue("department", "CS");
$stmt->bindValue("course", "CS290");
$stmt->bindValue("year", 2016);
$stmt->bindValue("title", "Web Development");
$stmt->execute();

// Display values

echo "<!doctype html>\n";

$stmt = $db->prepare("SELECT * FROM users");
$stmt->execute();
echo "<h1>Users</h1>\n";
echo "<table border=1>\n";
echo "  <tr><th>id<th>name<th>email</tr>\n";
foreach ($stmt as $row) {
    echo '  <tr>';
    echo '<td>'.htmlspecialchars($row['id']).'</td>';
    echo '<td>'.htmlspecialchars($row['name']).'</td>';
    echo '<td>'.htmlspecialchars($row['email']).'</td>';
    echo "</tr>\n";
}
echo "</table>\n";

$stmt = $db->prepare("SELECT * FROM courses");
$stmt->execute();
echo "<h1>Courses</h1>\n";
echo "<table border=1>\n";
echo "  <tr><th>id<th>dept<th>course<th>title<th>year</tr>\n";
foreach ($stmt as $row) {
    echo '  <tr>';
    echo '<td>'.htmlspecialchars($row['id']).'</td>';
    echo '<td>'.htmlspecialchars($row['department']).'</td>';
    echo '<td>'.htmlspecialchars($row['course']).'</td>';
    echo '<td>'.htmlspecialchars($row['title']).'</td>';
    echo '<td>'.htmlspecialchars($row['year']).'</td>';
    echo "</tr>\n";
}
echo "</table>\n";

?>
