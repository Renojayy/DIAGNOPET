<?php
include 'db_connect.php';

$sql = "CREATE TABLE IF NOT EXISTS symptoms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT NOT NULL,
    symptom TEXT NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE
);";

if ($conn->query($sql) === TRUE) {
    echo "Symptoms table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
