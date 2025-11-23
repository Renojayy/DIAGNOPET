<?php
session_start();
include 'db_connect.php';

// Prevent back button after login by disabling caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect logged-in vets away from login page
if (isset($_SESSION['vet_logged_in']) && $_SESSION['vet_logged_in'] === true) {
    header("Location: termsandconditions_vet.php");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Query the database including status
        $stmt = $conn->prepare("SELECT id, name, password, status FROM veterinarian WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $name, $hashed_password, $status);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();

            // Check approval status
            if ($status !== 'approved') {
                $error = "Your account is still pending review. Please wait for approval before logging in.";
            } else if (password_verify($password, $hashed_password)) {
                // Successful login — store session
                $_SESSION['vet_id'] = $id;
                $_SESSION['vet_name'] = $name;
                $_SESSION['vet_email'] = $email;
                $_SESSION['vet_logged_in'] = true;

                // Redirect to dashboard
                header("Location: dashboard_vet.php");
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
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Vet Login - Diagnopet</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4 overflow-hidden">

<div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative z-10 backdrop-blur-sm bg-white/90">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Vet Login</h2>
        <p class="text-gray-600">Welcome back to Diagnopet</p>
        <?php if (isset($error)) echo "<p class='text-red-500 mt-2'>$error</p>"; ?>
    </div>

    <form action="" method="POST" class="space-y-4">
        <input type="email" name="email" placeholder="Email Address" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        <input type="password" name="password" placeholder="Password" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition">
            Login
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="vetlanding.php" class="text-gray-600 hover:text-blue-500">Back to previous page</a>
        <p class="mt-2">Don't have an account? <a href="vet-register.php" class="text-blue-500">Register Here</a></p>
    </div>
</div>

<script>
feather.replace();
</script>
</body>
</html>
