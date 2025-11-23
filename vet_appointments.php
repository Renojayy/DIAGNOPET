<?php
session_start();
include 'db_connect.php';

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
$user_id = $_SESSION['vet_id'];

// Handle form submission for scheduling appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_appointment'])) {
    $consultation_id = $_POST['consultation_id'];
    $pet_owner = $_POST['pet_owner'];
    $vet_license_number = $_POST['vet_license_number'];
    $consultation_date = $_POST['consultation_date'];
    $symptoms_discussed = $_POST['symptoms_discussed'];
    $remarks = $_POST['remarks'];
    $level_of_threats = $_POST['level_of_threats'];

    // Insert into database (assuming a table named 'consultations')
    $sql = "INSERT INTO consultations (consultation_id, `Pet Owner`, `Veterinary License Number`, `Consultations Date`, `Symptoms Discussed`, Remarks, `Level of Threats`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $consultation_id, $pet_owner, $vet_license_number, $consultation_date, $symptoms_discussed, $remarks, $level_of_threats);
    $stmt->execute();
    $stmt->close();

    // Redirect or show success message
    header("Location: vet_appointments.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Vet Appointments - Diagnopet</title>
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
      <a class="nav-link" href="dashboard_vet.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a class="nav-link active" href="vet_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a>
      <a class="nav-link" href="vet_billing.php"><i class="fas fa-credit-card"></i> Billing</a>
      <a class="nav-link" href="vet_settings.php"><i class="fas fa-cog"></i> Settings</a>
      <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
  </div>

  <div class="main-content">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Appointment scheduled successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>All Vet Appointments</h2>
      <div>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal"><i class="fas fa-calendar-plus"></i> Schedule New</button>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5><i class="fas fa-calendar"></i> All Vet Appointments</h5>
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
              <tr>
                <td>Max</td>
                <td>Surgery</td>
                <td><span class="badge bg-danger rounded-pill">May 19 10:00 AM</span></td>
              </tr>
              <tr>
                <td>Bella</td>
                <td>Check-up</td>
                <td><span class="badge bg-primary rounded-pill">May 20 11:00 AM</span></td>
              </tr>
              <tr>
                <td>Charlie</td>
                <td>Vaccination</td>
                <td><span class="badge bg-warning rounded-pill">May 21 2:00 PM</span></td>
              </tr>
              <tr>
                <td>Luna</td>
                <td>Grooming</td>
                <td><span class="badge bg-info rounded-pill">May 22 9:00 AM</span></td>
              </tr>
              <tr>
                <td>Rocky</td>
                <td>Dental</td>
                <td><span class="badge bg-success rounded-pill">May 23 4:00 PM</span></td>
              </tr>
              <tr>
                <td>Daisy</td>
                <td>Check-up</td>
                <td><span class="badge bg-primary rounded-pill">May 24 10:30 AM</span></td>
              </tr>
              <tr>
                <td>Oliver</td>
                <td>Surgery</td>
                <td><span class="badge bg-danger rounded-pill">May 25 1:00 PM</span></td>
              </tr>
              <tr>
                <td>Sophie</td>
                <td>Vaccination</td>
                <td><span class="badge bg-warning rounded-pill">May 26 3:00 PM</span></td>
              </tr>
              <tr>
                <td>Milo</td>
                <td>Water Change</td>
                <td><span class="badge bg-secondary rounded-pill">May 27 11:00 AM</span></td>
              </tr>
              <tr>
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

  <!-- Modal for Scheduling Appointment -->
  <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="scheduleModalLabel">Schedule New Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="consultation_id" class="form-label">Consultation ID</label>
                  <input type="text" class="form-control" id="consultation_id" name="consultation_id" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="pet_owner" class="form-label">Pet Owner</label>
                  <input type="text" class="form-control" id="pet_owner" name="pet_owner" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="mb-3">
                  <label for="consultation_date" class="form-label">Consultation Date</label>
                  <input type="datetime-local" class="form-control" id="consultation_date" name="consultation_date" required>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="symptoms_discussed" class="form-label">Symptoms Discussed</label>
              <textarea class="form-control" id="symptoms_discussed" name="symptoms_discussed" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="remarks" class="form-label">Remarks</label>
              <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="level_of_threats" class="form-label">Level of Threats</label>
              <select class="form-control" id="level_of_threats" name="level_of_threats" required>
                <option value="">Select Level</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Critical">Critical</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="schedule_appointment" class="btn btn-primary">Schedule Appointment</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
