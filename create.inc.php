<?php

require_once "config.php";

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
$db->exec('DROP TABLE IF EXISTS user_courses');
$db->exec('DROP TABLE IF EXISTS courses');
$db->exec('DROP TABLE IF EXISTS users');

// Create tables

$db->exec('
CREATE TABLE users (
    id INTEGER,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    -- TIMESTAMP is a year 2038 bug waiting to happen,
    -- but CURRENT_TIMESTAMP wont work with DATETIME
    -- until MySQL 5.6
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Optional data
    phone VARCHAR(255),
    -- major_id INTEGER,
    -- standing_id INTEGER,

    -- FOREIGN KEY (major_id) REFERENCES majors (id),
    -- FOREIGN KEY (standing_id) REFERENCES class_standings (id),
    UNIQUE (email),
    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE courses (
    id INTEGER,
    department VARCHAR(255),    -- eg CS
    course VARCHAR(255),        -- eg CS290
    year INTEGER,               -- eg 2016
    title VARCHAR(255),         -- eg Web Development
    -- TODO(ae): term instead of year?

    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE user_courses (
    user_id INTEGER,
    course_id INTEGER, -- TODO(ae): course_section_id instead of course_id?

    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (course_id) REFERENCES courses (id),
    PRIMARY KEY (user_id, course_id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE groups (
    id INTEGER,
    course_id INTEGER,

    FOREIGN KEY (course_id) REFERENCES courses (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE group_members (
    group_id INTEGER,
    user_id INTEGER,

    FOREIGN KEY (group_id) REFERENCES groups (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    PRIMARY KEY (group_id, user_id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

// Insert values

$stmt = $db->prepare("INSERT INTO users (id, email, name, phone) VALUES (:id, :email, :name, :phone)");

$stmt->bindValue("id", 1);
$stmt->bindValue("email", "bob@oregonstate.edu");
$stmt->bindValue("name", "Robert'); DROP TABLE Students;-- ");
$stmt->bindValue("phone", null);
$stmt->execute();

$stmt->bindValue("id", 2);
$stmt->bindValue("email", "ekstedta@oregonstate.edu");
$stmt->bindValue("name", "Andrew Ekstedt");
$stmt->bindValue("phone", null);
$stmt->execute();

$stmt->bindValue("id", 3);
$stmt->bindValue("email", "sunxiao@oregonstate.edu");
$stmt->bindValue("name", "Xiaoli Sun");
$stmt->bindValue("phone", "8005550001");
$stmt->execute();

$stmt->bindValue("id", 4);
$stmt->bindValue("email", "michaelj@oregonstate.edu");
$stmt->bindValue("name", "Michael Jordan");
$stmt->bindValue("phone", "8005550002");
$stmt->execute();

$stmt->bindValue("id", 5);
$stmt->bindValue("email", "marchisc@oregonstate.edu");
$stmt->bindValue("name", "Claudio Marchisio");
$stmt->bindValue("phone", "8005550003");
$stmt->execute();

$stmt->bindValue("id", 6);
$stmt->bindValue("email", "chathamb@oregonstate.edu");
$stmt->bindValue("name", "Brandon Chatham");
$stmt->bindValue("phone", "8005550004");
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

$stmt = $db->prepare("INSERT INTO user_courses (user_id, course_id) VALUE (:user_id, :course_id)");

$stmt->bindValue("user_id", 2);         # Andrew
$stmt->bindValue("course_id", 1);       # CS290
$stmt->execute();

$stmt->bindValue("user_id", 3);         # Xiaoli
$stmt->bindValue("course_id", 1);
$stmt->execute();

$stmt->bindValue("user_id", 6);         # Brandon
$stmt->bindValue("course_id", 1);
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

$db = null;

include 'display.php';

?>
