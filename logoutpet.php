<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();

// Redirect to guest dashboard after logout
header("Location: petowner_guest_dashboard.php");
exit();
