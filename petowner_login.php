<?php
session_start();
include 'db_connect.php';

// Prevent back button caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect logged-in pet owners away from login page
if (isset($_SESSION['petowner_logged_in']) && $_SESSION['petowner_logged_in'] === true) {
    header("Location: petowner_dashboard.php");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("SELECT owner_id, Name, Password FROM petowners WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($owner_id, $name, $hashed_password);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['petowner_id'] = $owner_id;
                $_SESSION['petowner_name'] = $name;
                $_SESSION['petowner_email'] = $email;
                $_SESSION['petowner_logged_in'] = true;

                header("Location: petowner_dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pet Owner Login - Diagnopet</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">

<div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Pet Owner Login</h2>
        <p class="text-gray-600">Welcome back to Diagnopet</p>
        <?php if (isset($error)) echo "<p class='text-red-500 mt-2'>$error</p>"; ?>
    </div>

    <form action="" method="POST" class="space-y-4">
        <input type="email" name="email" placeholder="Email Address" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
        <input type="password" name="password" placeholder="Password" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 rounded-lg">
            Login
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="landing_petowner.php" class="text-gray-600 hover:text-blue-500">Back to previous page</a>
        <p class="mt-2">Don't have an account? <a href="register.php" class="text-blue-500">Register Here</a></p>
    </div>
</div>

</body>
</html>
