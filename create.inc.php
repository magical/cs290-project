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
    department VARCHAR(255),    -- eg CS
    course VARCHAR(255),        -- eg CS290
    year INTEGER,               -- eg 2016
    title VARCHAR(255),         -- eg Web Development

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
$stmt->bindValue("name", "Robert'); DROP TABLE Students;-- ");
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

$stmt->bindValue("id", 2);
$stmt->bindValue("department", "MTH");
$stmt->bindValue("course", "MTH252");
$stmt->bindValue("year", 2016);
$stmt->bindValue("title", "Integral Calculus");
$stmt->execute();

$stmt = $db->prepare("INSERT INTO groups (id, course_id) VALUES (:id, :course_id)");

$stmt->bindValue("id", 1);
$stmt->bindValue("course_id", 1);
$stmt->execute();
$stmt->bindValue("id", 2);
$stmt->bindValue("course_id", 1);
$stmt->execute();

$stmt = $db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)");

$stmt->bindValue("group_id", 1);
$stmt->bindValue("user_id", 1);
$stmt->execute();

$stmt->bindValue("group_id", 1);
$stmt->bindValue("user_id", 2);
$stmt->execute();

$stmt->bindValue("group_id", 2);
$stmt->bindValue("user_id", 2);
$stmt->execute();

// Display values

echo '<!doctype html>';
include 'view.inc.php';

?>
