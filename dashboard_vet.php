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
      <a class="nav-link" href="#appointments"><i class="fas fa-calendar-check"></i> Appointments</a>
      <a class="nav-link" href="#patients"><i class="fas fa-users"></i> Patients</a>
      <a class="nav-link" href="#billing"><i class="fas fa-credit-card"></i> Billing</a>
      <a class="nav-link" href="#settings"><i class="fas fa-cog"></i> Settings</a>
      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
      <div>
        <button class="btn btn-primary me-2"><i class="fas fa-plus"></i> Add Patient</button>
        <button class="btn btn-outline-primary"><i class="fas fa-calendar-plus"></i> Schedule Appointment</button>
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
            <h5><i class="fas fa-users"></i> Recent Patients</h5>
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
        <div class="card">
          <div class="card-header">
            <h5><i class="fas fa-calendar"></i> Upcoming Appointments</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Buddy - Check-up
                <span class="badge bg-primary rounded-pill">Today 10:00 AM</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Whiskers - Vaccination
                <span class="badge bg-primary rounded-pill">Today 2:00 PM</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Tweetie - Grooming
                <span class="badge bg-secondary rounded-pill">Tomorrow 9:00 AM</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Goldie - Water Change
                <span class="badge bg-secondary rounded-pill">Tomorrow 11:00 AM</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Fluffy - Dental
                <span class="badge bg-secondary rounded-pill">May 18 3:00 PM</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
