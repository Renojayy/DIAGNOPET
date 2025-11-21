<?php
include 'db_connect.php';

// Backup existing data
$sql_backup = "CREATE TABLE pets_backup AS SELECT * FROM pets";
if ($conn->query($sql_backup) !== TRUE) {
    die("Error backing up table: " . $conn->error);
}

// Drop the old table
$sql_drop = "DROP TABLE pets";
if ($conn->query($sql_drop) !== TRUE) {
    die("Error dropping table: " . $conn->error);
}

// Create new table with id
$sql_create = "CREATE TABLE pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `Pet Type` VARCHAR(255),
    `Pet Name` VARCHAR(255),
    `Pet Gender` VARCHAR(255),
    `Pet Weight` VARCHAR(255),
    `Pet Breed` VARCHAR(255),
    `Pet Age` VARCHAR(255),
    user_name VARCHAR(255),
    avatar VARCHAR(255)
)";
if ($conn->query($sql_create) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Insert data back
$sql_insert = "INSERT INTO pets (`Pet Type`, `Pet Name`, `Pet Gender`, `Pet Weight`, `Pet Breed`, `Pet Age`, user_name, avatar) SELECT * FROM pets_backup";
if ($conn->query($sql_insert) !== TRUE) {
    die("Error inserting data: " . $conn->error);
}

// Drop backup
$sql_drop_backup = "DROP TABLE pets_backup";
if ($conn->query($sql_drop_backup) !== TRUE) {
    die("Error dropping backup: " . $conn->error);
}

echo "Table 'pets' recreated with 'id' column successfully.";

$conn->close();
?>
