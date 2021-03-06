<?php

// create.php - (re)creates the database and populates with test data

die("disabled");

require_once "includes/all.php";

// Connect to the database

$db = connect_db();

// Drop tables

$db->exec('DROP TABLE IF EXISTS group_posts');
$db->exec('DROP TABLE IF EXISTS group_members');
$db->exec('DROP TABLE IF EXISTS groups');
$db->exec('DROP TABLE IF EXISTS user_courses');
$db->exec('DROP TABLE IF EXISTS courses');
$db->exec('DROP TABLE IF EXISTS users');
$db->exec('DROP TABLE IF EXISTS pic');

$db->exec('DROP TABLE IF EXISTS buildings');
$db->exec('DROP TABLE IF EXISTS campuses');
$db->exec('DROP TABLE IF EXISTS colleges');
$db->exec('DROP TABLE IF EXISTS standings');


// Create tables

$db->exec('
CREATE TABLE campuses (
    id INTEGER AUTO_INCREMENT,
    name VARCHAR(255),

    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE colleges (
    id INTEGER AUTO_INCREMENT,
    abbreviation VARCHAR(5),
    name VARCHAR(255),

    UNIQUE (abbreviation),
    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE standings (
    id INTEGER AUTO_INCREMENT,
    name VARCHAR(255),

    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE pic (
    id INTEGER NOT NULL AUTO_INCREMENT ,
    filename VARCHAR(255),
    filedata MEDIUMBLOB,
    filesize INTEGER,
    mimetype VARCHAR(20),

    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');


$db->exec('
CREATE TABLE users (
    id INTEGER AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    -- TIMESTAMP is a year 2038 bug waiting to happen,
    -- but CURRENT_TIMESTAMP wont work with DATETIME
    -- until MySQL 5.6
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Optional data
    password_hash VARCHAR(255),
    phone VARCHAR(255),
    campus_id INTEGER,
    college_id INTEGER,
    standing_id INTEGER,
    pic_id INTEGER,

    -- Study times
    day1 VARCHAR(10), -- Monday - Sunday
    time1 INTEGER, -- 0-23

    day2 VARCHAR(10),
    time2 INTEGER,

    FOREIGN KEY (campus_id) REFERENCES campuses (id),
    FOREIGN KEY (college_id) REFERENCES colleges (id),
    FOREIGN KEY (standing_id) REFERENCES standings (id),
    FOREIGN KEY (pic_id) REFERENCES pic (id),
    UNIQUE (email),
    INDEX (name),
    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE courses (
    id INTEGER AUTO_INCREMENT,
    department VARCHAR(10),     -- eg CS
    number VARCHAR(10),         -- eg 290
    title VARCHAR(255),         -- eg Web Development
    year INTEGER,               -- eg 2016
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
    id INTEGER AUTO_INCREMENT,
    course_id INTEGER,
    is_private BOOLEAN DEFAULT FALSE,
    
    name VARCHAR(255) NOT NULL,
    day VARCHAR(10), -- Monday - Sunday
    time INTEGER, -- 0-23
    place VARCHAR(255),
	 campus VARCHAR(255),
    blurb TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
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

// A group_post is a short message posted to a group page.
$db->exec('
CREATE TABLE group_posts (
    id INTEGER AUTO_INCREMENT,
    group_id    INTEGER,
    user_id     INTEGER,
    body        TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (group_id) REFERENCES groups (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    PRIMARY KEY (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

$db->exec('
CREATE TABLE buildings (
    building_id INTEGER AUTO_INCREMENT,
    building_abbr VARCHAR(255) NOT NULL,
    cam_id INTEGER NOT NULL,


    PRIMARY KEY (building_id),
    FOREIGN KEY (cam_id) REFERENCES campuses (id)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

// Insert values

$stmt = $db->prepare("INSERT INTO standings (id, name) VALUES (:id, :name)");
$stmt->execute(array('id' => 1, 'name' => 'First-year'));
$stmt->execute(array('id' => 2, 'name' => 'Second-year'));
$stmt->execute(array('id' => 3, 'name' => 'Third-year'));
$stmt->execute(array('id' => 4, 'name' => 'Fourth-year'));
$stmt->execute(array('id' => 5, 'name' => 'Fifth-year or more'));

$stmt = $db->prepare("INSERT INTO campuses (id, name) VALUES (:id, :name)");
$stmt->execute(array('id' => 1, 'name' => 'Corvallis (Main)'));
$stmt->execute(array('id' => 2, 'name' => 'Cascades'));
$stmt->execute(array('id' => 3, 'name' => 'Online'));

$stmt = $db->prepare("INSERT INTO colleges (abbreviation, name) VALUES (:abbreviation, :name)");
$stmt->execute(array('abbreviation' => 'Agr', 'name' => 'Agricultural Sciences'));
$stmt->execute(array('abbreviation' => 'Bus', 'name' => 'Business'));
$stmt->execute(array('abbreviation' => 'Ear', 'name' => 'Earth, Ocean, and Atmospheric Sciences'));
$stmt->execute(array('abbreviation' => 'Edu', 'name' => 'Education'));
$stmt->execute(array('abbreviation' => 'Eng', 'name' => 'Engineering'));
$stmt->execute(array('abbreviation' => 'For', 'name' => 'Forestry'));
$stmt->execute(array('abbreviation' => 'Gra', 'name' => 'Graduate School')); // Do we want this option?
$stmt->execute(array('abbreviation' => 'Lib', 'name' => 'Liberal Arts'));
$stmt->execute(array('abbreviation' => 'Pha', 'name' => 'Pharmacy'));
$stmt->execute(array('abbreviation' => 'Pub', 'name' => 'Public Health and Human Services'));
$stmt->execute(array('abbreviation' => 'Sci', 'name' => 'Science'));
$stmt->execute(array('abbreviation' => 'Vet', 'name' => 'Veterinary Medicine'));

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
// This is a fun one.
// Insert rows into the courses table from catalog.csv

$fp = fopen("catalog.csv", "r");
if ($fp === false) {
  die("couldn't open catalog.csv");
}

$header = fgetcsv($fp);
if ($header !== array("department", "number", "title", "term")) {
  die("catalog.csv is not in the correct format");
}

$stmt = $db->prepare("INSERT INTO courses (department, number, year, title) VALUES (:department, :number, :year, :title)");

while ($row = fgetcsv($fp)) {
  // ugh
  $row['department'] = $row[0];
  $row['number'] = $row[1];
  $row['title'] = $row[2];
  $row['term'] = $row[3];

  // ignore honors courses
  if (substr($row['number'], -1) == 'H') {
    continue;
  }

  // just import winter term for now
  if ($row['term'] === "Winter 2016") {
    $stmt->bindValue("department", $row['department']);
    $stmt->bindValue('number', $row['number']);
    $stmt->bindValue('title', $row['title']);
    $stmt->bindValue('year', 2016);
    $stmt->execute();
  }
}
fclose($fp);

// Grab a course id for the next couple inserts.
$stmt = $db->query("SELECT id FROM courses WHERE department='CS' AND number='290'");
$cs290_id = $stmt->fetch()['id'];

$stmt = $db->prepare("INSERT INTO user_courses (user_id, course_id) VALUE (:user_id, :course_id)");

$stmt->bindValue("user_id", 2);         # Andrew
$stmt->bindValue("course_id", $cs290_id);       # CS290
$stmt->execute();

$stmt->bindValue("user_id", 3);         # Xiaoli
$stmt->bindValue("course_id", $cs290_id);
$stmt->execute();

$stmt->bindValue("user_id", 6);         # Brandon
$stmt->bindValue("course_id", $cs290_id);
$stmt->execute();

$stmt = $db->prepare("INSERT INTO groups (id, course_id, name) VALUES (:id, :course_id, :name)");

$stmt->bindValue("id", 1);
$stmt->bindValue("course_id", $cs290_id);
$stmt->bindValue("name", "Group 1");
$stmt->execute();
$stmt->bindValue("id", 2);
$stmt->bindValue("course_id", $cs290_id);
$stmt->bindValue("name", "Group 2");
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

$stmt = $db->prepare("INSERT INTO buildings (building_id, building_abbr, cam_id) VALUES (:building_id, :building_abbr, :cam_id)");

$stmt->bindValue("building_id", 1);
$stmt->bindValue("building_abbr", "LINC");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 2);
$stmt->bindValue("building_abbr", "KEC");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 3);
$stmt->bindValue("building_abbr", "VLib");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 4);
$stmt->bindValue("building_abbr", "MU");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 5);
$stmt->bindValue("building_abbr", "ALS");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 6);
$stmt->bindValue("building_abbr", "ILLC");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 7);
$stmt->bindValue("building_abbr", "Kidd");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 8);
$stmt->bindValue("building_abbr", "Wngr");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 9);
$stmt->bindValue("building_abbr", "Bexl");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 10);
$stmt->bindValue("building_abbr", "StAg");
$stmt->bindValue("cam_id", 1);
$stmt->execute();


$stmt->bindValue("building_id", 11);
$stmt->bindValue("building_abbr", "Snel");
$stmt->bindValue("cam_id", 1);
$stmt->execute();

// Close database connection

$db = null;

// Display values

include 'display.php';
