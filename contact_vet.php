<?php
session_start();
include 'db_connect.php';

// Check login without redirecting (used for modal)
$userLoggedIn = isset($_SESSION['petowner_logged_in']);

if (!isset($_GET['vet_id'])) {
    die("No veterinarian selected.");
}

$vet_id = intval($_GET['vet_id']);
$stmt = $conn->prepare("SELECT name, email FROM veterinarian WHERE id=?");
$stmt->bind_param("i", $vet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Veterinarian not found.");
}

$vet = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Veterinarian</title>
<style>
body {
    margin: 0;
    padding: 0;
    background: #111e2bff;
    font-family: 'Poppins', sans-serif;
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    background-color: #2a95faff;
    color: white;
    width: 220px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    top: 0;
    left: 0;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    font-weight: 600;
}
.sidebar a {
    color: white;
    text-decoration: none;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}
.sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.2); }
.sidebar .back-btn {
    position: sticky;
    bottom: 20px;
    margin-top: auto;
    background: white;
    color: #2a95faff;
    text-align: center;
    padding: 10px 0;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
    border: none;
    transition: background-color 0.3s ease;
}
.sidebar .back-btn:hover { background-color: #d0d0ff; }

.main-content {
    margin-left: 220px;
    padding: 40px 20px 40px 40px;
    width: 100%;
}

/* Message box */
.message-box {
    width: 700px;
    background: white;
    border-radius: 18px;
    box-shadow: 0px 6px 20px rgba(0,0,0,0.15);
    overflow: hidden;
    margin: 0 auto;
}
.header {
    background: #2a95faff;
    padding: 16px 22px;
    color: white;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.input-field {
    width: 90%;
    margin: 15px auto;
    background: #e0e0ff;
    border-radius: 25px;
    padding: 12px 18px;
    font-size: 16px;
    border: none;
    outline: none;
}
textarea {
    width: 92%;
    height: 280px;
    margin: 10px auto;
    padding: 18px;
    border-radius: 12px;
    border: none;
    outline: none;
    font-size: 16px;
    resize: none;
    background: #f0f0ff;
    display: block;
}
.tools {
    background: #e0e0ff;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 18px;
    font-size: 20px;
}
.button-row {
    padding: 16px 20px;
    background: #e0e0ff;
    display: flex;
    justify-content: flex-end;
    gap: 14px;
}
.btn {
    padding: 10px 22px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 15px;
}
.send-btn { background:#2a95faff; color: white; }
.save-btn, .cancel-btn { background: white; color: #5c4fff; }

/* LOGIN REQUIRED MODAL */
#loginModal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
#loginModal .modal-box {
    background: white;
    padding: 30px;
    width: 350px;
    border-radius: 14px;
    text-align: center;
}
#loginModal h2 { margin-bottom: 10px; font-size: 22px; color: #5c4fff; }
#loginModal button {
    padding: 10px 20px;
    background: #5c4fff;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
</style>
</head>

<body>

<!-- LOGIN REQUIRED MODAL -->
<div id="loginModal">
    <div class="modal-box">
        <h2>Login Required</h2>
        <p>You must log in to contact a veterinarian.</p>
        <button onclick="window.location.href='petowner_login.php'">Go to Login</button>
    </div>
</div>

<div class="sidebar">
    <a href="#" class="active">New Chat</a>
    <a href="#">Inbox</a>
    <a href="#">Outbox</a>
    <a href="#">Sent</a>
    <button class="back-btn" onclick="window.location.href='vets.php'">Back</button>
</div>

<div class="main-content">
<div class="message-box">
    <div class="header">
        New Message
        <div style="font-size:18px; cursor:pointer;">— □ ×</div>
    </div>

    <input type="text" class="input-field" value="Dr. <?php echo htmlspecialchars($vet['name']); ?>" readonly>

    <select class="input-field">
        <option disabled selected>Select Subject</option>
        <option>Check Up Appointment</option>
        <option>Groom Booking (If available)</option>
        <option>Pet Vaccination</option>
        <option>Others</option>
    </select>

    <textarea placeholder="Write your message here..."></textarea>

    <div class="tools">✒️ 📎 🙂 🖼️ 🗑️ ☰</div>

    <div class="button-row">
        <button class="btn send-btn">Send</button>
        <button class="btn save-btn">Save</button>
        <button class="btn cancel-btn" onclick="history.back()">Cancel</button>
    </div>
</div>
</div>

<script>
<?php if (!$userLoggedIn): ?>
document.getElementById("loginModal").style.display = "flex";
<?php endif; ?>
</script>

</body>
</html>
