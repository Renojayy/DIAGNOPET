<?php
include 'db_connect.php';

$sql = "ALTER TABLE consultations ADD COLUMN Pet_Owner VARCHAR(100), ADD COLUMN vet_license_number VARCHAR(100);";

if ($conn->query($sql) === TRUE) {
    echo "Table altered successfully";
} else {
    echo "Error altering table: " . $conn->error;
}

$conn->close();
?>
