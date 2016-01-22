<?php

require_once "config.php";

// Connect to the database

try {
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database";
    die();
}

// Drop tables

$db->exec('DROP TABLE IF EXISTS accounts');

// Create tables

$db->exec('
CREATE TABLE accounts (
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    pass_hash VARCHAR(60) NOT NULL,

    PRIMARY KEY (email)
) ENGINE=InnoDB, CHARACTER SET=UTF8');

// Insert values

$stmt = $db->prepare("INSERT INTO accounts (email, username, pass_hash) VALUES (:email, :username, :pass_hash)");

$stmt->bindValue("email", "user@host.tld");
$stmt->bindValue("username", "user");
$stmt->bindValue("pass_hash", password_hash("pass", PASSWORD_BCRYPT));
$stmt->execute();

?>