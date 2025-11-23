<?php
// Page to inform vets they must wait for account review after registration
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Account Pending Review - Diagnopet</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="style.css" />
</head>
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4 overflow-auto">
  <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Account Pending Review</h2>
    <p class="text-gray-600 mb-6">
      Thank you for registering with Diagnopet.<br/>
      Your account is pending review. Please wait up to 24 hours for approval.<br/>
    </p>
    <a href="vet-login.php" class="inline-block bg-primary hover:bg-primary/90 text-blue font-medium py-2 px-6 rounded-lg transition">
      Go to Login Page
    </a>
  </div>
</body>
</html>
