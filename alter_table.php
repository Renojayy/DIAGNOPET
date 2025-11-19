<?php
include 'db_connect.php';

$sql = "ALTER TABLE pets ADD COLUMN user_name VARCHAR(100), ADD COLUMN avatar VARCHAR(255);";

if ($conn->query($sql) === TRUE) {
    echo "Table altered successfully";
} else {
    echo "Error altering table: " . $conn->error;
}

$conn->close();
?>
