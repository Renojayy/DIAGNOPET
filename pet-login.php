<?php
session_start();
include 'db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $sql = "SELECT owner_id, Name, Password FROM petowners WHERE Name = ? OR Email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['Password'])) {
                $_SESSION['petowner_logged_in'] = true;
                $_SESSION['user_name'] = $row['Name'];
                $_SESSION['owner_id'] = $row['owner_id'];
                // Redirect to full-access dashboard
                header("Location: petowner_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>DIAGNOPET - Pet Owner Login</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root{
    --bg:#f3f6ff; --card:#ffffff; --muted:#8b8fb1; --accent:#5c4fff; --glass: rgba(255,255,255,0.7);
    --shadow: 0 6px 18px rgba(36,41,90,0.08);
}
body{font-family:Inter,system-ui,Arial; margin:0; background: linear-gradient(180deg,#eef2ff 0%, #f7f9ff 100%); display:flex; justify-content:center; align-items:center; height:100vh;}
.login-card{background:var(--card); padding:40px; border-radius:14px; box-shadow:var(--shadow); width:100%; max-width:400px;}
h2{text-align:center; color:var(--accent);}
form{display:flex; flex-direction:column; gap:16px;}
input{padding:12px; border-radius:8px; border:1px solid #ddd; font-size:14px;}
button{padding:12px; border:none; border-radius:8px; background:var(--accent); color:#fff; font-size:16px; cursor:pointer;}
.error{color:red; font-size:14px; text-align:center;}
.link{margin-top:12px; text-align:center; font-size:14px;}
.link a{color:var(--accent); text-decoration:none;}
</style>
</head>
<body>
<div class="login-card">
    <h2>Pet Owner Login</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Name or Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <div class="link">
        <a href="register.php">Don't have an account? Register</a>
    </div>
</div>
</body>
</html>
