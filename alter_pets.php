<?php
include 'db_connect.php';

$sql = "ALTER TABLE pets ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;";

if ($conn->query($sql) === TRUE) {
    echo "Column 'id' added successfully to 'pets' table";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();
?>
