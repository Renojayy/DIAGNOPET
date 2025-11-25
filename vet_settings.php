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

include 'db_connect.php';

$user_name = $_SESSION['vet_name'];

// Initialize variables for form fields and errors
$vet = [
    'name' => '',
    'specialization' => '',
    'license_number' => '',
    'email' => '',
    'clinic_address' => '',
    'city' => '',
    'clinic_name' => '',
    'expiration_date' => '',
    'prc_id_path' => '',
];

// Load vet info from DB by vet name
$stmt = $conn->prepare("SELECT name, specialization, license_number, email, clinic_address, city, clinic_name, expiration_date, prc_id_path, password FROM veterinarian WHERE name = ?");
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
    .delete-account-btn {
      background-color: #ff4d4f;
      border-color: #ff4d4f;
      color: white;
      font-weight: bold;
      text-align: center;
      transition: background-color 0.3s, border-color 0.3s;
    }
    .delete-account-btn:hover {
      background-color: #d9363e;
      border-color: #d9363e;
      color: white;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h4 class="mb-4"><i class="fas fa-user-md"></i> Vet Dashboard</h4>
    <nav class="nav flex-column">
      <a class="nav-link" href="dashboard_vet.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a class="nav-link" href="vet_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a>
      <a class="nav-link active" href="vet_settings.php"><i class="fas fa-cog"></i> Settings</a>
      <!-- Logout button triggers confirmation modal -->
      <a href="logout.php" id="logoutBtn" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
            <a href="updateprofilevets.php" class="setting-btn">
              <i class="fas fa-user"></i> Profile Settings
            </a>
<a href="vet_billing.php" class="setting-btn" id="billingPaymentsBtn">
  <i class="fas fa-credit-card"></i> Billing & Payments
</a>
<a href="#" class="setting-btn" id="helpSupportBtn">
  <i class="fas fa-question-circle"></i> Help & Support
</a>

<!-- Modal for Help & Support -->
<div class="modal fade" id="helpSupportModal" tabindex="-1" aria-labelledby="helpSupportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpSupportModalLabel">Help & Support</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="white-space: pre-line;">
        If you need assistance, have questions, or encounter any issues, our support team is here to help. Please feel free to contact us anytime at diagnopet9@gmail.com, and we’ll be glad to assist you promptly.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
            <a href="#" id="aboutDiagnopetBtn" class="setting-btn">
              <i class="fas fa-info-circle"></i> About Diagnopet
            </a>
            </form>
            <!-- Delete Account Button -->
            <button id="deleteAccountBtn" type="button" class="setting-btn delete-account-btn">
              <i class="fas fa-trash-alt"></i> DELETE ACCOUNT
            </button>

            <!-- Modal for About Diagnopet -->
            <div class="modal fade" id="aboutDiagnopetModal" tabindex="-1" aria-labelledby="aboutDiagnopetModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="aboutDiagnopetModalLabel">About Diagnopet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" style="white-space: pre-line;">
                    What is Diagnopet? It is a web-based diagnostic support system created to assist pet owners in understanding their pets’ health conditions. Our platform is designed to provide a preliminary evaluation of symptoms based on the information you provide, offering helpful insights before consulting a professional veterinarian.
                    At Diagnopet, we believe that early awareness leads to better care. Our goal is to empower pet owners with accessible, reliable, and user-friendly tools that promote proactive pet health management. Whether it’s a sudden change in behavior or visible symptoms, Diagnopet helps you take the first step in identifying possible conditions and making informed decisions for your pet’s well-being.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal for confirmation -->
            <div id="confirmModal" class="modal" style="display:none; position: fixed; z-index: 1050; top: 0; left: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
              <div class="modal-content" style="background-color: white; margin: 10% auto; padding: 20px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); max-width: 400px; text-align: center;">
                <p style="font-size: 18px; margin-bottom: 20px;">Are you sure you want to delete your Account? This action cannot be undo.</p>
                <form method="post" style="display:inline;">
                <div style="display: flex; justify-content: center; gap: 15px;">
                  <form method="post" style="display:inline;">
                    <button type="submit" name="delete_account" value="yes" class="btn btn-danger" style="min-width: 80px;">Yes</button>
                  </form>
                  <button id="cancelBtn" class="btn btn-primary" style="min-width: 80px;">No</button>
                </div>
              </div>
            </div>

            <!-- Modal for Logout confirmation -->
            <div id="logoutConfirmModal" class="modal" style="display:none; position: fixed; z-index: 1050; top: 0; left: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
              <div class="modal-content" style="background-color: white; margin: 10% auto; padding: 20px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); max-width: 400px; text-align: center;">
                <p style="font-size: 18px; margin-bottom: 20px;">Are you sure you want to log out?</p>
                <div style="display: flex; justify-content: center; gap: 15px;">
                  <button id="logoutYesBtn" class="btn btn-danger" style="min-width: 80px;">Yes</button>
                  <button id="logoutNoBtn" class="btn btn-primary" style="min-width: 80px;">No</button>
                </div>
              </div>
            </div>
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

<!-- Modal for Billing & Payments -->
<div class="modal fade" id="billingPaymentsModal" tabindex="-1" aria-labelledby="billingPaymentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="billingPaymentsModalLabel">Billing & Payments</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="white-space: pre-line;">
        Set up and manage your financial account to securely receive payments and ensure smooth transaction processing.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Proceed</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Remove the old confirm function

  // New JS for modal handling
  document.getElementById('deleteAccountBtn').addEventListener('click', function() {
    document.getElementById('confirmModal').style.display = 'block';
  });

  document.getElementById('cancelBtn').addEventListener('click', function() {
    document.getElementById('confirmModal').style.display = 'none';
  });

  // Close modal if clicked outside modal content
  window.onclick = function(event) {
    const modal = document.getElementById('confirmModal');
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  };

  // Show About Diagnopet Modal on button click
  document.getElementById('aboutDiagnopetBtn').addEventListener('click', function(event) {
    event.preventDefault();
    // Bootstrap 5 modal show
    var aboutModal = new bootstrap.Modal(document.getElementById('aboutDiagnopetModal'), {});
    aboutModal.show();
  });

  // Initialize Bootstrap tooltip for Help & Support button
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  // Show Help & Support Modal on button click
  document.getElementById('helpSupportBtn').addEventListener('click', function(event) {
    event.preventDefault();
    var helpModal = new bootstrap.Modal(document.getElementById('helpSupportModal'), {});
    helpModal.show();
  });

  // Show Billing & Payments Modal on button click
  document.getElementById('billingPaymentsBtn').addEventListener('click', function(event) {
    event.preventDefault();
    var billingModal = new bootstrap.Modal(document.getElementById('billingPaymentsModal'), {});
    billingModal.show();
  });

  // Alert for not implemented buttons
  const alertNotImplemented = (event) => {
    event.preventDefault();
    alert('This feature is not implemented yet.');
  };

  // Attach alerts

  // Logout modal handling
  const logoutBtn = document.getElementById('logoutBtn');
  const logoutConfirmModal = document.getElementById('logoutConfirmModal');
  const logoutYesBtn = document.getElementById('logoutYesBtn');
  const logoutNoBtn = document.getElementById('logoutNoBtn');

  if (logoutBtn && logoutConfirmModal && logoutYesBtn && logoutNoBtn) {
    logoutBtn.addEventListener('click', function(event) {
      event.preventDefault();
      logoutConfirmModal.style.display = 'block';
    });

    logoutYesBtn.addEventListener('click', function() {
      window.location.href = 'logout.php';
    });

    logoutNoBtn.addEventListener('click', function() {
      logoutConfirmModal.style.display = 'none';
    });
  }

  // Close modal if clicked outside modal content (applies for both confirmModal and logoutConfirmModal)
  window.onclick = function(event) {
    const confirmModal = document.getElementById('confirmModal');
    const logoutModal = document.getElementById('logoutConfirmModal');
    if (event.target == confirmModal) {
      confirmModal.style.display = 'none';
    }
    if (event.target == logoutModal) {
      logoutModal.style.display = 'none';
    }
  };
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
