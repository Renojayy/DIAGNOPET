<?php
session_start();

// Prevent back navigation
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check login
if (!isset($_SESSION['petowner_logged_in'])) {
    header("Location: role-select.php");
    exit();
}

// Handle account deletion (POST from modal form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    include 'db_connect.php';

    // Use owner_id as unique identifier
    $owner_id = isset($_SESSION['owner_id']) ? intval($_SESSION['owner_id']) : 0;

    if ($owner_id > 0) {
        $conn->begin_transaction();
        try {
            $id = $owner_id;

            // Delete from related tables first, then petowner
 $queries = [
    "DELETE FROM pets WHERE user_name = '$u'",
    "DELETE FROM symptoms WHERE user_name = '$u'",
    "DELETE FROM medications WHERE user_name = '$u'",
    "DELETE FROM petowners WHERE owner_id = $owner_id"
];
            foreach ($queries as $q) {
                if (!$conn->query($q)) {
                    throw new Exception("DB error: " . $conn->error);
                }
            }

            $conn->commit();

            // Destroy session and redirect
            session_unset();
            session_destroy();
            header("Location: account_deleted.php");
            exit();

        } catch (Exception $ex) {
            $conn->rollback();
            $delete_error = "Failed to delete account: " . htmlspecialchars($ex->getMessage());
        }

    } else {
        $delete_error = "Unable to identify your account.";
    }
}

// Static content variables
$help_support_content = "If you need assistance, have questions, or encounter any issues, our support team is here to help. 
Please feel free to contact us anytime at diagnopet9@gmail.com, and we’ll be glad to assist you promptly.";

$about_diagnopet_content = "What is Diagnopet? It is a web-based diagnostic support system created to assist pet owners 
in understanding their pets’ health conditions. Our platform provides a preliminary evaluation of symptoms based 
on the information you provide.

At Diagnopet, we believe that early awareness leads to better care. Our goal is to empower pet owners with accessible, 
reliable, and user-friendly tools that promote proactive pet health management.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Help & Support - Diagnopet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    body {
      background: #eef3ff;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }
    .main-wrapper {
      max-width: 1100px;
      margin: 40px auto;
      padding: 28px;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }
    .back-btn {
      background-color: #4a6cf7;
      color: white;
      padding: 8px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      display: inline-block;
      margin-bottom: 20px;
    }
    .diag-card {
      background: white;
      border-radius: 14px;
      padding: 18px;
      box-shadow: 0 6px 14px rgba(0,0,0,0.04);
      height: 100%;
    }
    .action-btn {
      padding: 12px 18px;
      border-radius: 10px;
      width: 100%;
      font-size: 16px;
      font-weight: 600;
      border: none;
    }
    .btn-delete {
      background-color: #ff4b4b;
      color: white;
    }
    .btn-delete:hover {
      background-color: #d63a3a;
      color: white;
    }
    @media (max-width: 576px) {
      .main-wrapper {
        margin: 16px;
        padding: 16px;
      }
    }
  </style>
</head>
<body>

<div class="main-wrapper">

  <a href="javascript:history.back()" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>

  <h2 class="mb-4" style="font-weight:700;">Help & Support</h2>

  <?php if (!empty($delete_error)): ?>
    <div class="alert alert-danger"><?php echo $delete_error; ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="diag-card">
        <h5><i class="fas fa-question-circle"></i> Help & Support</h5>
        <p><?php echo nl2br(htmlspecialchars($help_support_content)); ?></p>
      </div>
    </div>

    <div class="col-md-6">
      <div class="diag-card">
        <h5><i class="fas fa-info-circle"></i> About Diagnopet</h5>
        <p><?php echo nl2br(htmlspecialchars($about_diagnopet_content)); ?></p>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-center mt-3">
        <button class="action-btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal">
          <i class="fas fa-user-slash me-2"></i> Delete My Account
        </button>
      </div>
    </div>
  </div>

</div>

<!-- DELETE ACCOUNT MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-circle text-danger me-2"></i>Confirm Account Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST">
        <div class="modal-body">
          <p>Are you sure you want to delete your account?</p>
          <p class="text-danger fw-semibold">This action is permanent and cannot be undone. All your data (pets, symptoms, medications, account) will be removed.</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="delete_account" class="btn btn-danger">Yes, Delete My Account</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
