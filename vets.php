<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];

// Fetch all vets
$vets = [];
$sqlVets = "SELECT id, name, email, specialization, clinic_name, clinic_address, city, latitude, longitude 
            FROM veterinarian";
$result = $conn->query($sqlVets);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vets[] = $row;
    }
}

// Fetch unique clinics
$uniqueClinics = [];
$sqlClinics = "SELECT DISTINCT clinic_name, clinic_address, city 
               FROM veterinarian 
               WHERE clinic_name IS NOT NULL AND clinic_address IS NOT NULL";
$resultClinics = $conn->query($sqlClinics);
if ($resultClinics && $resultClinics->num_rows > 0) {
    while ($row = $resultClinics->fetch_assoc()) {
        $uniqueClinics[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vet Profiles</title>
<style>
body { margin:0; font-family:Poppins,sans-serif; background:#a0c7f1; padding:20px 0; }
.header { display:flex; justify-content:flex-start; align-items:center; padding:20px 40px; background:#5c4fff; color:#fff; margin-bottom:20px; gap:20px; flex-wrap:wrap; }
.tab {
    background: rgba(255,255,255,0.2);
    border-radius: 5px 5px 0 0;
    padding: 12px 24px;
    font-weight: 600;
    cursor: pointer;
    color: white;
    user-select: none;
    transition: background-color 0.3s ease;
}

.about-paragraph {
    margin-top: 16px;
    margin-bottom: 16px;
    font-family: Poppins, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    color: #333;
}
.tab.active {
    background: white;
    color: #5c4fff;
}
.container { display:flex; gap:20px; flex-wrap:wrap; justify-content:flex-start; }
.card { width:320px; background:white; border-radius:24px; box-shadow:0 8px 20px rgba(0,0,0,0.08); overflow:hidden; transition:0.25s ease; }
.card:hover { transform:translateY(-6px); box-shadow:0 12px 28px rgba(0,0,0,0.12); }
.placeholder-icon { display:flex; align-items:center; justify-content:center; height:200px; background:#f0f0f0; color:#666; font-size:100px; }
.content { padding:20px 24px; }
.name { font-size:20px; font-weight:600; display:flex; align-items:center; gap:6px; }
.subtext { font-size:14px; color:#666; margin-top:6px; }
.stats {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 12px 24px;
    gap: 12px;
    flex-wrap: nowrap;
}
.small {
    font-size: 14px;
    color: #444;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-grow: 1;
    justify-content: flex-start;
}
.btn {
    background: #5c4fff;
    color: white;
    padding: 10px 20px;
    border-radius: 40px;
    border: none;
    cursor: pointer;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease;
    min-width: 110px;
    justify-content: center;
}
.btn:hover {
    background: #4a3ecc;
}
.no-vets, .no-clinics { text-align:center; color:#666; font-size:18px; padding:40px; }

/* Modal styles with fade-in/out */
#aboutModal {
    display:none;
    position:fixed;
    top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0);
    justify-content:center;
    align-items:center;
    z-index:1000;
    padding:20px;
    opacity:0;
    transition: opacity 0.3s ease;
}
#aboutModal.show {
    display:flex;
    opacity:1;
    background: rgba(0,0,0,0.6);
}
#aboutModal .modal-content {
    background:white;
    border-radius:12px;
    max-width:500px;
    width:100%;
    padding:30px;
    position:relative;
    box-shadow:0 5px 15px rgba(0,0,0,0.3);
    text-align:left;
    transform: translateY(-30px);
    transition: transform 0.3s ease;
}
#aboutModal.show .modal-content {
    transform: translateY(0);
}
#aboutModal .modal-content h2 { margin-top:0; color:#5c4fff; }
#aboutModal .close-btn {
    position:absolute; top:10px; right:15px;
    background:none; border:none; font-size:24px;
    cursor:pointer; color:#555;
}
@media (max-width: 600px) {
    .card {
        width: 100%;
    }
    .header {
        flex-direction: column;
        gap: 10px;
    }
    .stats {
        justify-content: center;
        padding: 10px 16px;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn {
        min-width: auto;
        width: 100%;
        justify-content: center;
        padding: 12px 0;
    }
}
</style>
<script>
// Tab switching
function switchTab(tab) {
    document.getElementById('tab-veterinarians').classList.remove('active');
    document.getElementById('tab-clinics').classList.remove('active');
    document.getElementById('vets-container').style.display = 'none';
    document.getElementById('clinics-container').style.display = 'none';
    if (tab === 'veterinarians') {
        document.getElementById('tab-veterinarians').classList.add('active');
        document.getElementById('vets-container').style.display = 'flex';
    } else if (tab === 'clinics') {
        document.getElementById('tab-clinics').classList.add('active');
        document.getElementById('clinics-container').style.display = 'flex';
    }
}

// Contact button alert
function contactVetAlert(vetName) {
    alert("Log in and message " + vetName + " now!");
}

// View map
function viewMap(address) {
    const query = encodeURIComponent(address);
    const url = `https://www.google.com/maps/search/?api=1&query=${query}`;
    window.open(url, '_blank');
}

// Modal functions
function openAboutModal() {
    const modal = document.getElementById('aboutModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}
function closeAboutModal() {
    const modal = document.getElementById('aboutModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 300); // match transition
}
window.onclick = function(event) {
    const modal = document.getElementById('aboutModal');
    if (event.target === modal) {
        closeAboutModal();
    }
};
</script>
</head>
<body>

<div class="header">
  <div id="tab-veterinarians" class="tab active" onclick="switchTab('veterinarians')">Veterinarians</div>
  <div id="tab-clinics" class="tab" onclick="switchTab('clinics')">Veterinary Clinics</div>
  <a href="petowner_dashboard.php" style="margin-left:auto; color:#fff; font-weight:bold; text-decoration:none; cursor:pointer; padding:0;">Home</a>
  <a href="javascript:void(0);" onclick="openAboutModal()" style="color:#fff; font-weight:bold; margin-left:10px; text-decoration:none; cursor:pointer;">About</a>
  <a href="login.php" class="btn" style="background:#fff; color:#000; font-weight:bold; margin-left:10px;">Log in</a>
</div>

<div class="main-content">
  <!-- Vets Tab -->
  <div id="vets-container" class="container">
    <?php if (empty($vets)): ?>
      <div class="no-vets">No veterinarians found.</div>
    <?php else: ?>
      <?php foreach ($vets as $vet): ?>
        <div class='card'>
          <div class='placeholder-icon'>👨‍⚕️</div>
          <div class='content'>
            <div class='name'><?php echo htmlspecialchars($vet['name']); ?></div>
            <div class='subtext'>Specialization: <?php echo htmlspecialchars($vet['specialization']); ?></div>
            <div class='subtext'>Clinic: <?php echo htmlspecialchars($vet['clinic_name']); ?></div>
            <div class='subtext'>Location: <?php echo htmlspecialchars($vet['clinic_address'] . ', ' . $vet['city']); ?></div>
          </div>
          <div class='stats'>
            <div class='small'>📧 <?php echo htmlspecialchars($vet['email']); ?></div>
            <button class='btn' onclick="contactVetAlert('<?php echo addslashes($vet['name']); ?>')">Contact ✉️</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Clinics Tab -->
  <div id="clinics-container" class="container" style="display:none;">
    <?php if (empty($uniqueClinics)): ?>
      <div class="no-clinics">No clinics found.</div>
    <?php else: ?>
      <?php foreach ($uniqueClinics as $clinic): ?>
        <div class='card'>
          <div class='placeholder-icon'>🏥</div>
          <div class='content'>
            <div class='name'><?php echo htmlspecialchars($clinic['clinic_name']); ?></div>
            <div class='subtext'>Address: <?php echo htmlspecialchars($clinic['clinic_address']); ?></div>
            <div class='subtext'>City: <?php echo htmlspecialchars($clinic['city']); ?></div>
          </div>
          <div class='stats'>
            <button class='btn' onclick="viewMap('<?php echo addslashes(htmlspecialchars($clinic['clinic_address'] . ', ' . $clinic['city'])); ?>')">View Map 🗺️</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- About Modal -->
<div id="aboutModal">
    <div class="modal-content">
        <h2>About Diagnopet Vets and Clinics</h2>
        <p class="about-paragraph">
            The veterinarians and veterinary clinics featured on this page are officially registered members of the Diagnopet System. Diagnopet serves as a reliable bridge connecting pet owners with verified veterinary professionals and clinics, thereby ensuring consistent access to high-quality care for pets. By utilizing our platform, pet owners are empowered to confidently locate, contact, and engage with qualified veterinary services in a secure and efficient manner.
        </p>
        <button class="close-btn" onclick="closeAboutModal()">&times;</button>
    </div>
</div>

</body>
</html>