<?php
include 'db_connect.php';

$sql = "CREATE TABLE IF NOT EXISTS pet_adding (
    pet_id INT AUTO_INCREMENT PRIMARY KEY,
    pet_name VARCHAR(255) NOT NULL,
    species VARCHAR(255) NOT NULL,
    breed VARCHAR(255) NOT NULL,
    age VARCHAR(255) NOT NULL,
    owner_name VARCHAR(255) NOT NULL,
    status VARCHAR(255) DEFAULT 'Active'
);";

if ($conn->query($sql) === TRUE) {
    echo "Table 'pet_adding' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
