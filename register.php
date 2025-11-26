<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $name = $first_name . ' ' . $last_name;
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($contact) || empty($address)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT owner_id FROM petowners WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // Insert into petowners
            $stmt = $conn->prepare("INSERT INTO petowners (Name, Email, Password, ContactNo, Address) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $password, $contact, $address);

            if ($stmt->execute()) {
                header("Location: pet-login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
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
<title>Pet Owner Registration - Diagnopet</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4 overflow-auto">

<div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative z-10 backdrop-blur-sm bg-white/90">
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-blue-200/50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-feather="user-plus" class="text-blue-500 w-8 h-8"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Pet Owner Registration</h2>
        <p class="text-gray-600">Join Diagnopet today</p>
        <?php if (isset($error)) echo "<p class='text-red-500 mt-2'>$error</p>"; ?>
    </div>

    <form action="" method="POST" class="space-y-4">
        <div class="flex gap-4 w-full">
            <input type="text" name="first_name" placeholder="First Name" required
                class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <input type="text" name="last_name" placeholder="Last Name" required
                class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        <input type="email" name="email" placeholder="Email Address" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">

        <input type="text" name="contact" placeholder="Contact Number" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">

        <input type="text" name="address" placeholder="Address" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">

        <input type="password" name="password" placeholder="Password" required
               class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">

        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition">
            Register Account
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="landing_petowner.php" class="text-gray-600 hover:text-blue-500">Back to previous page</a>
        <p class="mt-2">Already have an account? <a href="pet-login.php" class="text-blue-500">Login Here</a></p>
    </div>
</div>

<script>
    feather.replace();
</script>

</body>
</html>
