<?php
include 'db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);
    $license_number = trim($_POST['license_number']);
    $verification_status = isset($_POST['verification']) ? $_POST['verification'] : 'not_verified';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = trim($_POST['email']);
    $location = trim($_POST['location']);
    $clinic_name = trim($_POST['clinic_name']);

    // Basic validation
    if (empty($name) || empty($specialization) || empty($license_number) || empty($password) || empty($email) || empty($location) || empty($clinic_name)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM veterinarian WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // Insert new vet
            $stmt = $conn->prepare("INSERT INTO veterinarian (name, specialization, license_number, verification_status, password, email, location, clinic_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $name, $specialization, $license_number, $verification_status, $password, $email, $location, $clinic_name);
            if ($stmt->execute()) {
                header("Location: vet-login.php");
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
  <title>Vet Registration - Diagnopet</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="style.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#3B82F6',
            secondary: '#10B981',
            accent: '#F59E0B'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4 overflow-auto">
  <!-- Floating decorative elements -->
  <div class="absolute w-32 h-32 rounded-full bg-primary/10 top-1/4 left-1/5 animate-float"></div>
  <div class="absolute w-24 h-24 rounded-full bg-secondary/10 bottom-1/4 right-1/5 animate-float-delay"></div>
  <div class="absolute w-20 h-20 rounded-full bg-accent/10 top-1/3 right-1/4 animate-float-delay-2"></div>
  <div class="absolute w-16 h-16 rounded-full bg-primary/10 bottom-1/3 left-1/4 animate-float"></div>

  <!-- Main card -->
  <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative z-10 backdrop-blur-sm bg-white/90">
    <div class="text-center mb-8">
      <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <i data-feather="user-plus" class="text-primary w-8 h-8"></i>
      </div>
      <h2 class="text-3xl font-bold text-gray-800 mb-2">Vet Registration</h2>
      <p class="text-gray-600">Join Diagnopet now</p>
      <?php if (isset($error)) echo "<p class='text-red-500'>$error</p>"; ?>
    </div>

    <form id="vetForm" action="vet-register.php" method="POST" class="space-y-4">
      <div class="relative">
        <i data-feather="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="name" id="name" placeholder="Name" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="briefcase" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="specialization" id="specialization" placeholder="Specialization" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="file-text" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="license_number" id="license_number" placeholder="License Number" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="space-y-2">
        <label class="block text-gray-700 font-medium">Verification Status</label>
        <div class="flex items-center space-x-4">
          <label class="flex items-center">
            <input type="radio" name="verification" value="verified" class="mr-2">
            Verify
          </label>
          <label class="flex items-center">
            <input type="radio" name="verification" value="not_verified" checked class="mr-2">
            Non Verify
          </label>
        </div>
      </div>

      <div class="relative">
        <i data-feather="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="password" name="password" id="password" placeholder="Password" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="mail" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="email" name="email" id="email" placeholder="Email" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="map-pin" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="location" id="location" placeholder="Location" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="home" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="clinic_name" id="clinic_name" placeholder="Clinic Name" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
        <i data-feather="user-plus" class="w-5 h-5"></i>
        Register Account
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="vetlanding.php" class="text-gray-600 hover:text-primary transition flex items-center justify-center gap-1">
        <i data-feather="arrow-left" class="w-4 h-4"></i>
        Back to previous page
      </a>
<p>
    Already have an Account? 
    <a href="vet-login.php" style="color: blue;">Click Here</a>
</p>
    </div>
  </div>

  <script>
    feather.replace();
  </script>
</body>
</html>
