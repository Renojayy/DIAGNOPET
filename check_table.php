<?php
include 'db_connect.php';

$sql = "DESCRIBE pets";
$result = $conn->query($sql);

if ($result) {
    echo "Table structure for 'pets':\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Key'] . "\n";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
