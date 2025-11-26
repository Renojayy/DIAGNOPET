<?php
session_start();
include 'db_connect.php';

// Check login status
$userLoggedIn = false;
$user_name = '';

if (isset($_SESSION['petowner_logged_in']) && $_SESSION['petowner_logged_in'] === true) {
    $userLoggedIn = true;
    $user_name = $_SESSION['petowner_name'] ?? '';
}

// Fetch all vets
$vets = [];
$sqlVets = "SELECT id, name, email, specialization, clinic_name, clinic_address, city, latitude, longitude 
            FROM veterinarian";
$result = $conn->query($sqlVets);
if ($result && $result->num_rows > 0) {
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
/* ======== UI Styles (keep your existing styling intact) ======== */
body { margin:0; font-family:Poppins,sans-serif; background:#a0c7f1; padding:20px 0; }
.header { display:flex; justify-content:flex-start; align-items:center; padding:20px 40px; background:#5c4fff; color:#fff; margin-bottom:20px; gap:20px; flex-wrap:wrap; }
.tab { background: rgba(255,255,255,0.2); border-radius: 5px 5px 0 0; padding: 12px 24px; font-weight: 600; cursor: pointer; color: white; user-select: none; transition: background-color 0.3s ease; }
.tab.active { background: white; color: #5c4fff; }
.container { display:flex; gap:20px; flex-wrap:wrap; justify-content:flex-start; }
.card { width:320px; background:white; border-radius:24px; box-shadow:0 8px 20px rgba(0,0,0,0.08); overflow:hidden; transition:0.25s ease; }
.card:hover { transform:translateY(-6px); box-shadow:0 12px 28px rgba(0,0,0,0.12); }
.placeholder-icon { display:flex; align-items:center; justify-content:center; height:200px; background:#f0f0f0; color:#666; font-size:100px; }
.content { padding:20px 24px; }
.name { font-size:20px; font-weight:600; display:flex; align-items:center; gap:6px; }
.subtext { font-size:14px; color:#666; margin-top:6px; }
.stats { display: flex; align-items: center; justify-content: flex-end; padding: 12px 24px; gap: 12px; flex-wrap: nowrap; }
.small { font-size: 14px; color: #444; display: flex; align-items: center; gap: 6px; flex-grow: 1; justify-content: flex-start; }
.btn { background: #5c4fff; color: white; padding: 10px 20px; border-radius: 40px; border: none; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px; text-decoration: none; text-align: center; transition: background-color 0.3s ease; min-width: 110px; justify-content: center; }
.btn:hover { background: #4a3ecc; }
.no-vets, .no-clinics { text-align:center; color:#666; font-size:18px; padding:40px; }

/* About Modal */
#aboutModal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0); justify-content:center; align-items:center; z-index:1000; padding:20px; opacity:0; transition: opacity 0.3s ease; }
#aboutModal.show { display:flex; opacity:1; background: rgba(0,0,0,0.6); }
#aboutModal .modal-content { background:white; border-radius:12px; max-width:500px; width:100%; padding:30px; position:relative; box-shadow:0 5px 15px rgba(0,0,0,0.3); text-align:left; transform: translateY(-30px); transition: transform 0.3s ease; }
#aboutModal.show .modal-content { transform: translateY(0); }
#aboutModal .modal-content h2 { margin-top:0; color:#5c4fff; }
#aboutModal .close-btn { position:absolute; top:10px; right:15px; background:none; border:none; font-size:24px; cursor:pointer; color:#555; }

/* Contact Modal */
#contactModal { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6); display:none; justify-content:center; align-items:center; z-index:1001; }
#contactModal .modal-content { background:white; padding:30px; width:400px; border-radius:14px; text-align:center; }
#contactModal h2 { margin-bottom:15px; font-size:22px; }
#contactModal button { padding: 10px 20px; background:
#5c4fff; color: white; border: none; border-radius: 8px; cursor: pointer; margin-top:10px; }

@media (max-width: 600px) { .card { width:100%; } .header { flex-direction: column; gap: 10px; } .stats { justify-content: center; padding: 10px 16px; gap: 8px; flex-wrap: wrap; } .btn { min-width:auto; width:100%; justify-content:center; padding:12px 0; } }
</style>

<script>
// Correct login state
var isLoggedIn = <?php echo $userLoggedIn ? 'true' : 'false'; ?>;

// Tabs
function switchTab(tab) {
    document.getElementById('tab-veterinarians').classList.remove('active');
    document.getElementById('tab-clinics').classList.remove('active');
    document.getElementById('vets-container').style.display = 'none';
    document.getElementById('clinics-container').style.display = 'none';
    if(tab==='veterinarians'){document.getElementById('tab-veterinarians').classList.add('active');document.getElementById('vets-container').style.display='flex';}
    else if(tab==='clinics'){document.getElementById('tab-clinics').classList.add('active');document.getElementById('clinics-container').style.display='flex';}
}

// Map
function viewMap(address){ const query=encodeURIComponent(address); window.open(`https://www.google.com/maps/search/?api=1&query=${query}`,'_blank'); }

// About modal
function openAboutModal(){ const modal=document.getElementById('aboutModal'); modal.style.display='flex'; setTimeout(()=>modal.classList.add('show'),10);}
function closeAboutModal(){ const modal=document.getElementById('aboutModal'); modal.classList.remove('show'); setTimeout(()=>modal.style.display='none',300);}

// Contact modal
function openContactModal(vetId, vetName){
    const modal = document.getElementById('contactModal');
    const modalTitle = document.getElementById('contactModalTitle');
    const modalBody = document.getElementById('contactModalBody');

    if(isLoggedIn){
        modalTitle.innerText = "Contact " + vetName;
        modalBody.innerHTML = `<p>You are logged in. <br> 
                               <a href="contact_vet.php?vet_id=${vetId}">
                                 <button>Go to Message</button>
                               </a></p>`;
    } else {
        modalTitle.innerText = "Login Required";
        modalBody.innerHTML = `<p>You must log in to contact ${vetName}.</p>
                               <button onclick="window.location.href='petowner_login.php'">Go to Login</button>`;
    }

    modal.style.display='flex';
}
function closeContactModal(){ document.getElementById('contactModal').style.display='none'; }

window.onclick = function(event){
    if(event.target === document.getElementById('aboutModal')) closeAboutModal();
    if(event.target === document.getElementById('contactModal')) closeContactModal();
};
</script>
</head>
<body>

<div class="header">
  <div id="tab-veterinarians" class="tab active" onclick="switchTab('veterinarians')">Veterinarians</div>
  <div id="tab-clinics" class="tab" onclick="switchTab('clinics')">Veterinary Clinics</div>
  <a href="petowner_dashboard.php" style="margin-left:auto; color:#fff; font-weight:bold; text-decoration:none; cursor:pointer; padding:0;">Home</a>
  <a href="javascript:void(0);" onclick="openAboutModal()" style="color:#fff; font-weight:bold; margin-left:10px; text-decoration:none; cursor:pointer;">About</a>

</div>

<div class="main-content">
  <!-- Vets -->
  <div id="vets-container" class="container">
    <?php if(empty($vets)): ?>
        <div class="no-vets">No veterinarians found.</div>
    <?php else: ?>
        <?php foreach($vets as $vet): ?>
        <div class="card">
            <div class="placeholder-icon">👨‍⚕️</div>
            <div class="content">
                <div class="name"><?php echo htmlspecialchars($vet['name']); ?></div>
                <div class="subtext">Specialization: <?php echo htmlspecialchars($vet['specialization']); ?></div>
                <div class="subtext">Clinic: <?php echo htmlspecialchars($vet['clinic_name']); ?></div>
                <div class="subtext">Location: <?php echo htmlspecialchars($vet['clinic_address'] . ', ' . $vet['city']); ?></div>
            </div>
            <div class="stats">
                <div class="small">📧 <?php echo htmlspecialchars($vet['email']); ?></div>
                <button class="btn" onclick="openContactModal('<?php echo $vet['id']; ?>','<?php echo addslashes(htmlspecialchars($vet['name'])); ?>')">Contact ✉️</button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Clinics -->
  <div id="clinics-container" class="container" style="display:none;">
    <?php if(empty($uniqueClinics)): ?>
        <div class="no-clinics">No clinics found.</div>
    <?php else: ?>
        <?php foreach($uniqueClinics as $clinic): ?>
        <div class="card">
            <div class="placeholder-icon">🏥</div>
            <div class="content">
                <div class="name"><?php echo htmlspecialchars($clinic['clinic_name']); ?></div>
                <div class="subtext">Address: <?php echo htmlspecialchars($clinic['clinic_address']); ?></div>
                <div class="subtext">City: <?php echo htmlspecialchars($clinic['city']); ?></div>
            </div>
            <div class="stats">
                <button class="btn" onclick="viewMap('<?php echo addslashes(htmlspecialchars($clinic['clinic_address'] . ', ' . $clinic['city'])); ?>')">View Map 🗺️</button>
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
        <p><strong>The veterinarians and veterinary clinics featured on this page are officially registered members of the Diagnopet System.</strong></p>
        <p>Diagnopet serves as a <em>reliable bridge</em> connecting pet owners with verified veterinary professionals and clinics, thereby ensuring consistent access to high-quality care for pets.</p>
        <p>By utilizing our platform, pet owners are <strong>empowered</strong> to confidently locate, contact, and engage with qualified veterinary services in a secure and efficient manner.</p>
        <button class="close-btn" onclick="closeAboutModal()">&times;</button>
    </div>
</div>

<!-- Contact Modal -->
<div id="contactModal">
    <div class="modal-content">
        <h2 id="contactModalTitle"></h2>
        <div id="contactModalBody"></div>
        <button onclick="closeContactModal()">Close</button>
    </div>
</div>

</body>
</html>
