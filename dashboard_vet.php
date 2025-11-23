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
  <title>Vet Dashboard - Diagnopet</title>
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
    .table th {
      background-color: #007bff;
      color: white;
      border: none;
    }
    .table td {
      border: none;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
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
      <a class="nav-link active" href="#dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a class="nav-link" href="vet_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a>
      <a class="nav-link" href="#billing"><i class="fas fa-credit-card"></i> Billing</a>
      <a class="nav-link" href="vet_settings.php"><i class="fas fa-cog"></i> Settings</a>
      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
      <div class="d-flex">
        <div class="dropdown me-2">
          <button class="btn btn-outline-primary dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i> Notifications
          </button>
          <ul class="dropdown-menu" aria-labelledby="notificationDropdown">
            <li><h6 class="dropdown-header">Recent Notifications</h6></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-check"></i> Appointment reminder: Buddy check-up today at 10:00 AM</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-user-md"></i> New consultation request from John Doe</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-exclamation-triangle"></i> Urgent: Whiskers vaccination due</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center" href="#">View All Notifications</a></li>
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-primary dropdown-toggle" type="button" id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-envelope"></i> Messages
          </button>
          <ul class="dropdown-menu" aria-labelledby="messageDropdown">
            <li><h6 class="dropdown-header">Recent Messages</h6></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> John Doe: "Can we reschedule Buddy's appointment?"</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Jane Smith: "Thank you for the great service!"</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Vet Clinic: "New vaccination guidelines available."</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center" href="#">View All Messages</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <i class="fas fa-users fa-2x text-primary mb-2"></i>
            <h5 class="card-title">Total Patients</h5>
            <h3 class="text-primary">150</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
            <h5 class="card-title">Today's Appointments</h5>
            <h3 class="text-success">12</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
            <h5 class="card-title">Monthly Revenue</h5>
            <h3 class="text-warning">$5,200</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <i class="fas fa-star fa-2x text-info mb-2"></i>
            <h5 class="card-title">Average Rating</h5>
            <h3 class="text-info">4.8</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5><i class="fas fa-users"></i> Your Patients</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Species</th>
                    <th>Last Visit</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Buddy</td>
                    <td>Dog</td>
                    <td>2023-05-15</td>
                    <td><span class="badge bg-success">Healthy</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>Whiskers</td>
                    <td>Cat</td>
                    <td>2023-05-10</td>
                    <td><span class="badge bg-warning">Check-up</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>Tweetie</td>
                    <td>Bird</td>
                    <td>2023-05-08</td>
                    <td><span class="badge bg-info">Vaccination</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>Goldie</td>
                    <td>Fish</td>
                    <td>2023-05-05</td>
                    <td><span class="badge bg-success">Healthy</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>Fluffy</td>
                    <td>Rabbit</td>
                    <td>2023-05-03</td>
                    <td><span class="badge bg-danger">Sick</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card" id="appointments">
          <div class="card-header">
            <h5><i class="fas fa-calendar"></i>  Vet Appointments</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Pet Name</th>
                    <th>Type</th>
                    <th>Date & Time</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Buddy</td>
                    <td>Check-up</td>
                    <td><span class="badge bg-primary rounded-pill">Today 10:00 AM</span></td>
                  </tr>
                  <tr>
                    <td>Whiskers</td>
                    <td>Vaccination</td>
                    <td><span class="badge bg-primary rounded-pill">Today 2:00 PM</span></td>
                  </tr>
                  <tr>
                    <td>Tweetie</td>
                    <td>Grooming</td>
                    <td><span class="badge bg-secondary rounded-pill">Tomorrow 9:00 AM</span></td>
                  </tr>
                  <tr>
                    <td>Goldie</td>
                    <td>Water Change</td>
                    <td><span class="badge bg-secondary rounded-pill">Tomorrow 11:00 AM</span></td>
                  </tr>
                  <tr>
                    <td>Fluffy</td>
                    <td>Dental</td>
                    <td><span class="badge bg-secondary rounded-pill">May 18 3:00 PM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Max</td>
                    <td>Surgery</td>
                    <td><span class="badge bg-danger rounded-pill">May 19 10:00 AM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Bella</td>
                    <td>Check-up</td>
                    <td><span class="badge bg-primary rounded-pill">May 20 11:00 AM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Charlie</td>
                    <td>Vaccination</td>
                    <td><span class="badge bg-warning rounded-pill">May 21 2:00 PM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Luna</td>
                    <td>Grooming</td>
                    <td><span class="badge bg-info rounded-pill">May 22 9:00 AM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Rocky</td>
                    <td>Dental</td>
                    <td><span class="badge bg-success rounded-pill">May 23 4:00 PM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Daisy</td>
                    <td>Check-up</td>
                    <td><span class="badge bg-primary rounded-pill">May 24 10:30 AM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Oliver</td>
                    <td>Surgery</td>
                    <td><span class="badge bg-danger rounded-pill">May 25 1:00 PM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Sophie</td>
                    <td>Vaccination</td>
                    <td><span class="badge bg-warning rounded-pill">May 26 3:00 PM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Milo</td>
                    <td>Water Change</td>
                    <td><span class="badge bg-secondary rounded-pill">May 27 11:00 AM</span></td>
                  </tr>
                  <tr class="extra-appointments" style="display: none;">
                    <td>Zoe</td>
                    <td>Grooming</td>
                    <td><span class="badge bg-info rounded-pill">May 28 2:00 PM</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
