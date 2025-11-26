<?php
session_start();
// Just in case, destroy any lingering session
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account Deleted - Diagnopet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      background: #eef3ff;
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
    }
    .wrapper {
      background: #fff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.06);
      text-align: center;
      max-width: 500px;
    }
    .wrapper i {
      font-size: 60px;
      color: #ff4b4b;
      margin-bottom: 20px;
    }
    .wrapper h1 {
      font-weight: 700;
      margin-bottom: 15px;
    }
    .wrapper p {
      font-size: 16px;
      margin-bottom: 30px;
    }
    .btn-home {
      background-color: #4a6cf7;
      color: white;
      padding: 12px 25px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
    }
    .btn-home:hover {
      background-color: #3a54c7;
      color: white;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <i class="fas fa-user-slash"></i>
    <h1>Account Deleted</h1>
    <p>Your account and all associated data have been successfully removed from Diagnopet.</p>
    <a href="petowner_guest_dashboard.php" class="btn-home"><i class="fas fa-home me-2"></i>Go to Login Page</a>
  </div>
</body>
</html>
