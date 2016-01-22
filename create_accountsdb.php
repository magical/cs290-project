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

?>