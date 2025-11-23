<?php
session_start();

// Prevent back navigation
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is logged in
if (!isset($_SESSION['vet_logged_in']) || $_SESSION['vet_logged_in'] !== true) {
    header("Location: vet-login.php");
    exit();
}

// Check if terms are accepted
if (!isset($_SESSION['terms_accepted']) || $_SESSION['terms_accepted'] !== true) {
    header("Location: termsandconditions_vet.php");
    exit();
}

$user_name = $_SESSION['vet_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vet Settings - Diagnopet</title>
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
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .setting-btn {
      display: block;
      width: 100%;
      padding: 15px;
      margin-bottom: 10px;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      text-align: left;
      font-size: 16px;
      font-weight: 500;
      color: #495057;
      transition: all 0.3s;
      text-decoration: none;
    }
    .setting-btn:hover {
      background-color: #e9ecef;
      border-color: #adb5bd;
      color: #212529;
      text-decoration: none;
    }
    .setting-btn i {
      margin-right: 10px;
      width: 20px;
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
      <a class="nav-link active" href="vet_settings.php"><i class="fas fa-cog"></i> Settings</a>
      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Settings</h2>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5><i class="fas fa-cog"></i> Vet Settings</h5>
          </div>
          <div class="card-body">
            <a href="#" class="setting-btn">
              <i class="fas fa-user"></i> Profile Settings
            </a>
            <a href="#" class="setting-btn">
              <i class="fas fa-bell"></i> Notification Preferences
            </a>
            <a href="#" class="setting-btn">
              <i class="fas fa-lock"></i> Privacy Settings
            </a>
            <a href="#" class="setting-btn">
              <i class="fas fa-credit-card"></i> Billing & Payments
            </a>
            <a href="#" class="setting-btn">
              <i class="fas fa-shield-alt"></i> Security Settings
            </a>
            <a href="#" class="setting-btn">
              <i class="fas fa-language"></i> Language & Region
            </a>
            <a href="#" class="setting-btn">
              <i class="fas fa-question-circle"></i> Help & Support
            </a>
            <a href="#" class="setting-btn">
              <i class="fas fa-info-circle"></i> About Diagnopet
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h5><i class="fas fa-info"></i> Quick Info</h5>
          </div>
          <div class="card-body">
            <p><strong>Logged in as:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Role:</strong> Veterinarian</p>
            <p><strong>Last Login:</strong> Today</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
