<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'diagnopet';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['session_token'])) {
    header("Location: login.php?error=Please login first");
    exit();
}

// Get session token from DB
$stmt = $conn->prepare("SELECT session_token FROM users WHERE username = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $_SESSION['username']);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$stmt->bind_result($dbToken);
if (!$stmt->fetch()) {
    // No user found or fetch failed
    $stmt->close();
    header("Location: login.php?error=Invalid session");
    exit();
}
$stmt->close();

// Validate session token
if ($dbToken !== $_SESSION['session_token']) {
    header("Location: login.php?error=Invalid session");
    exit();
}
