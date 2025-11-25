<?php
session_start();

// Prevent back navigation
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if vet is logged in
if (!isset($_SESSION['vet_logged_in']) || $_SESSION['vet_logged_in'] !== true) {
    header("Location: vet-login.php");
    exit();
}

// Include database connection
include 'db_connect.php';

$user_name = $_SESSION['vet_name'];

// Initialize variables
$vet = [
    'name' => '',
    'license_number' => '',
    'email' => '',
    'clinic_name' => '',
    'clinic_address' => '',
    'city' => '',
];

$errors = [];
$success_msg = '';
$pass_errors = [];
$pass_success_msg = '';

// Load vet info from DB by name
$stmt = $conn->prepare("SELECT name, license_number, email, clinic_name, clinic_address, city, password FROM veterinarian WHERE name = ?");
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows === 1) {
    $vet = $result->fetch_assoc();
} else {
    // Vet not found, log out or error
    session_unset();
    session_destroy();
    header("Location: vet-login.php");
    exit();
}
$stmt->close();

// Parse name into first_name and last_name for form inputs
$first_name = '';
$last_name = '';
if (!empty($vet['name'])) {
    $name_parts = explode(' ', $vet['name'], 2);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
}

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Validate input
    $first_name_input = trim($_POST['first_name'] ?? '');
    $last_name_input = trim($_POST['last_name'] ?? '');
    $license_number = trim($_POST['license_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $clinic_name = trim($_POST['clinic_name'] ?? '');
    $clinic_address = trim($_POST['clinic_address'] ?? '');
    $city = trim($_POST['city'] ?? '');

    if ($first_name_input === '') {
        $errors[] = "First name is required.";
    }
    if ($last_name_input === '') {
        $errors[] = "Last name is required.";
    }
    if ($license_number === '') {
        $errors[] = "License number is required.";
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if ($clinic_name === '') {
        $errors[] = "Clinic name is required.";
    }
    if ($clinic_address === '') {
        $errors[] = "Clinic address is required.";
    }
    if ($city === '') {
        $errors[] = "City is required.";
    }

    if (empty($errors)) {
        // Prepare updated name by combining first and last
        $updated_name = $first_name_input . ' ' . $last_name_input;

        // Update DB
        $update_stmt = $conn->prepare("UPDATE veterinarian SET name = ?, license_number = ?, email = ?, clinic_name = ?, clinic_address = ?, city = ? WHERE name = ?");
        $update_stmt->bind_param("sssssss", $updated_name, $license_number, $email, $clinic_name, $clinic_address, $city, $user_name);
        if ($update_stmt->execute()) {
            $success_msg = "Profile updated successfully.";
            // Update session vet_name if name changed
            $_SESSION['vet_name'] = $updated_name;
            $user_name = $updated_name;
            $vet['name'] = $updated_name;
            $vet['license_number'] = $license_number;
            $vet['email'] = $email;
            $vet['clinic_name'] = $clinic_name;
            $vet['clinic_address'] = $clinic_address;
            $vet['city'] = $city;
            // Update first and last name variables too
            $first_name = $first_name_input;
            $last_name = $last_name_input;
        } else {
            $errors[] = "Error updating profile. Please try again.";
        }
        $update_stmt->close();
    }
}

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Fetch current password hash for verification
    $stmt_pass = $conn->prepare("SELECT password FROM veterinarian WHERE name = ?");
    $stmt_pass->bind_param("s", $user_name);
    $stmt_pass->execute();
    $result_pass = $stmt_pass->get_result();
    $stored_password_hash = '';
    if ($result_pass && $result_pass->num_rows === 1) {
        $row = $result_pass->fetch_assoc();
        $stored_password_hash = $row['password'];
    }
    $stmt_pass->close();

    // Validate passwords
    if ($old_password === '') {
        $pass_errors[] = "Old password is required.";
    } elseif (!password_verify($old_password, $stored_password_hash)) {
        $pass_errors[] = "Old password is incorrect.";
    }
    if ($new_password === '') {
        $pass_errors[] = "New password is required.";
    }
    if ($confirm_password === '') {
        $pass_errors[] = "Confirm password is required.";
    }
    if ($new_password !== $confirm_password) {
        $pass_errors[] = "New password and confirm password do not match.";
    }

    if (empty($pass_errors)) {
        // Hash new password and update DB
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_pass_stmt = $conn->prepare("UPDATE veterinarian SET password = ? WHERE name = ?");
        $update_pass_stmt->bind_param("ss", $new_password_hash, $user_name);
        if ($update_pass_stmt->execute()) {
            $pass_success_msg = "Password changed successfully.";
        } else {
            $pass_errors[] = "Error updating password. Please try again.";
        }
        $update_pass_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Profile - Diagnopet</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      background-color: #007bff;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }
    .sidebar {
      background-color: #007bff;
      color: white;
      min-height: 100vh;
      padding: 20px;
      position: fixed;
      width: 250px;
      left: 0;
      top: 0;
      z-index: 1000;
    }
    .sidebar .nav-link {
      color: white;
      font-weight: 500;
      padding: 10px 15px;
      margin-bottom: 5px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    .sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
    .sidebar .nav-link.active {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .main-content {
      margin-left: 250px;
      padding: 20px;
      background-color: white;
      min-height: 100vh;
    }
    h2 {
      color: #007bff;
      font-weight: 700;
      margin-bottom: 20px;
    }
    label {
      font-weight: 600;
    }
    .form-control:focus {
      box-shadow: 0 0 5px #007bff;
      border-color: #007bff;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #100d31ff;
      border-color: #100d31ff;
    }
    .error-msg {
      color: #dc3545;
      margin-bottom: 15px;
    }
    .success-msg {
      color: #28a745;
      margin-bottom: 15px;
    }
    .form-section {
      margin-bottom: 40px;
      border-bottom: 1px solid #e1e1e1;
      padding-bottom: 30px;
    }
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        position: relative;
        min-height: auto;
      }
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h4 class="mb-4"><i class="fas fa-user-md"></i> Vet Dashboard</h4>
    <nav class="nav flex-column">
      <a class="nav-link" href="dashboard_vet.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a class="nav-link" href="vet_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a>
      <a class="nav-link" href="#billing"><i class="fas fa-credit-card"></i> Billing</a>
      <a class="nav-link active" href="updateprofilevets.php"><i class="fas fa-user"></i> Profile Settings</a>
      <a class="nav-link" href="vet_settings.php"><i class="fas fa-cog"></i> Settings</a>
      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
  </div>

  <div class="main-content">
    <h2>Update Profile</h2>

    <?php if (!empty($errors)): ?>
      <div class="error-msg">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php elseif ($success_msg !== ''): ?>
      <div class="success-msg"><?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="update_profile" value="1" />
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="first_name" class="form-label">First Name</label>
          <input type="text" name="first_name" id="first_name" class="form-control" required value="<?php echo htmlspecialchars($first_name); ?>" />
        </div>
        <div class="col-md-6">
          <label for="last_name" class="form-label">Last Name</label>
          <input type="text" name="last_name" id="last_name" class="form-control" required value="<?php echo htmlspecialchars($last_name); ?>" />
        </div>
      </div>
      <div class="mb-3">
        <label for="license_number" class="form-label">License Number</label>
        <input type="text" name="license_number" id="license_number" class="form-control" required value="<?php echo htmlspecialchars($vet['license_number']); ?>" />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($vet['email']); ?>" />
      </div>
      <div class="mb-3">
        <label for="clinic_name" class="form-label">Clinic Name</label>
        <input type="text" name="clinic_name" id="clinic_name" class="form-control" required value="<?php echo htmlspecialchars($vet['clinic_name']); ?>" />
      </div>
      <div class="mb-3">
        <label for="clinic_address" class="form-label">Clinic Address</label>
        <input type="text" name="clinic_address" id="clinic_address" class="form-control" required value="<?php echo htmlspecialchars($vet['clinic_address']); ?>" />
      </div>
      <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" name="city" id="city" class="form-control" required value="<?php echo htmlspecialchars($vet['city']); ?>" />
      </div>
      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <div class="form-section"></div>

    <h2>Change Password</h2>

    <?php if (!empty($pass_errors)): ?>
      <div class="error-msg">
        <ul>
          <?php foreach ($pass_errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php elseif ($pass_success_msg !== ''): ?>
      <div class="success-msg"><?php echo htmlspecialchars($pass_success_msg); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="change_password" value="1" />
      <div class="mb-3">
        <label for="old_password" class="form-label">Old Password</label>
        <input type="password" name="old_password" id="old_password" class="form-control" required autocomplete="current-password" />
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label">New Password</label>
        <input type="password" name="new_password" id="new_password" class="form-control" required autocomplete="new-password" />
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required autocomplete="new-password" />
      </div>
      <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
