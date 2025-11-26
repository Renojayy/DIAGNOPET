<?php
session_start();
include 'db_connect.php';

// Ensure only logged-in petowners can access
if (!isset($_SESSION['petowner_logged_in']) || $_SESSION['petowner_logged_in'] !== true) {
    header("Location: petowner_guest_dashboard.php");
    exit();
}

$user_name = $_SESSION['user_name'];

// Fetch current user data
$stmt = $conn->prepare("SELECT Name, Email, ContactNo, Address FROM petowners WHERE Name = ?");
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    echo "User not found.";
    exit();
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($contact) || empty($address)) {
        $message = "All fields except password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (!empty($password) && $password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Update user data
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE petowners SET Name = ?, Email = ?, ContactNo = ?, Address = ?, Password = ? WHERE Name = ?");
            $stmt->bind_param("ssssss", $name, $email, $contact, $address, $hashed_password, $user_name);
        } else {
            $stmt = $conn->prepare("UPDATE petowners SET Name = ?, Email = ?, ContactNo = ?, Address = ? WHERE Name = ?");
            $stmt->bind_param("sssss", $name, $email, $contact, $address, $user_name);
        }

        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
            // Update session if name changed
            if ($name !== $user_name) {
                $_SESSION['user_name'] = $name;
                $user_name = $name;
            }
            // Refresh user data
            $stmt = $conn->prepare("SELECT Name, Email, ContactNo, Address FROM petowners WHERE Name = ?");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
        } else {
            $message = "Error updating profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIAGNOPET - Pet Owner Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f6ff;
            --card: #ffffff;
            --muted: #8b8fb1;
            --accent: #5c4fff;
            --glass: rgba(255,255,255,0.7);
            --shadow: 0 6px 18px rgba(36,41,90,0.08);
        }
        * { box-sizing: border-box; }
        body {
            font-family: Inter, system-ui, Arial;
            margin: 0;
            background: linear-gradient(180deg, #eef2ff 0%, #f7f9ff 100%);
            color: #222;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: var(--card);
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow);
        }
        h1 {
            text-align: center;
            color: var(--accent);
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            resize: vertical;
        }
        .btn {
            background: var(--accent);
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }
        .btn:hover {
            background: #4a3fff;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pet Owner Profile</h1>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['Name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['Email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number:</label>
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user_data['ContactNo']); ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($user_data['Address']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave blank to keep current):</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <button type="submit" class="btn">Update Profile</button>
        </form>

        <a href="petowner_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>
