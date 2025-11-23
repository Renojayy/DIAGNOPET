<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];

// Fetch vets from database
$vets = [];
$sql = "SELECT id, name, email, specialization, clinic_name, location FROM veterinarian";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vets[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Vet Profiles</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
  body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #a0c7f1ff;
    padding: 20px 0;
  }
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background: #5c4fff ;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
  }
  .header h1 {
    font-size: 24px;
    font-weight: 600;
    color: #ffff;
  }
  .back-btn {
    background: #ffff;
    color: #000;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    text-decoration: none;
    display: inline-block;
  }
  .main-content {
    display: flex;
    padding: 0 20px 0 20px;
    gap: 20px;
  }
  .map-widget {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto 20px auto;
    background: white;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #ddd;
    border-radius: 10px;
  }
  .map-widget h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
  }
  .widget {
    width: 255px;
    background: white;
    padding: 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-bottom: 20px;
  }
  .widget h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
  }
  .map-placeholder {
    width: 100%;
    height: 200px;
    background: #e0e0e0;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 16px;
  }
  .sidebar {
    width: 250px;
    background: #5c4fff ;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: fit-content;
  }
  .sidebar h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #fff;
  }
  .sidebar ul { list-style: none; padding: 0; }
  .sidebar li { margin-bottom: 10px; }
  .sidebar a { text-decoration: none; color: #fff; font-size: 16px; }
  .sidebar a:hover { color: #cce5ff; }

  #clinicMap {
    width: 100%;
    height: 170px;
    border-radius: 10px;
    margin-top: -14px;
    animation: fadeInMap 2s ease-in-out;
  }

  @keyframes fadeInMap {
    from {
      opacity: 0;
      transform: scale(0.9);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  .container {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
    justify-content: center;
    flex: 1;
  }
  .card {
    width: 320px;
    background: white;
    border-radius: 24px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: 0.25s ease;
  }
  .card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
  }
  .card img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    transition: 0.25s ease;
  }
  .card img:hover { filter: brightness(0.9); }

  .placeholder-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 350px;
    background: #f0f0f0;
    color: #666;
    font-size: 100px;
  }

  .content { padding: 20px 24px; }

  .name {
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .green-dot {
    width: 12px;
    height: 12px;
    background: #22c55e;
    border-radius: 50%;
  }
  .subtext { font-size: 14px; color: #666; margin-top: 6px; }
  .rating { margin-top: 8px; font-size: 14px; color: #f4b400; }

  .stats {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
  }
  .small {
    font-size: 14px;
    color: #444;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .btn {
    background: black;
    color: white;
    padding: 12px 22px;
    border-radius: 40px;
    border: none;
    cursor: pointer;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .no-vets {
    text-align: center;
    color: #666;
    font-size: 18px;
    padding: 40px;
  }

  /* Responsive */
  @media(max-width: 900px) {
    .main-content { flex-direction: column; align-items: center; }
    .sidebar { width: 90%; }
    .map-widget { width: 90%; }
  }
  @media(max-width: 768px) {
    .card { width: 90%; }
    .map-widget { width: 95%; }
  }

  /* --- Floating Bubbles --- */
  .bubble {
    position: absolute;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.15);
    animation: float 8s infinite ease-in-out;
    z-index: 0;
  }

  @keyframes float {
    0% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-20px) scale(1.1); }
    100% { transform: translateY(0) scale(1); }
  }
</style>
</head>
<body>
  <div class="bubble" style="width:120px;height:120px;top:20%;left:15%;animation-delay:0s;"></div>
  <div class="bubble" style="width:80px;height:80px;top:60%;left:70%;animation-delay:2s;"></div>
  <div class="bubble" style="width:100px;height:100px;top:40%;left:40%;animation-delay:4s;"></div>
  <div class="bubble" style="width:60px;height:60px;top:80%;left:25%;animation-delay:1s;"></div>

<div class="header">
  <h1>Veterinarians in Diagnopet</h1>
  <a href="petowner_dashboard.php" class="back-btn">Back</a>
</div>

<div class="main-content">
  <div class="sidebar">
    <h3>Filters</h3>
    <ul>
      <li><a href="#">Nearby</a></li>
      <li><a href="#">High Rating</a></li>
      <li><a href="#">Favorites</a></li>
      <li><a href="#">Newest</a></li>
    </ul>

    <div class="widget">
      <h4> Find Vet Clinics Places Near You!</h4>
      <div id="clinicMap"></div>
    </div>
  </div>

  <div class="container">
    <?php if (empty($vets)): ?>
      <div class="no-vets">
        No vets yet
      </div>
    <?php else: ?>
      <?php foreach ($vets as $vet): ?>
        <div class='card'>
          <div class='placeholder-icon'>👨‍⚕️</div>
          <div class='content'>
            <div class='name'><?php echo htmlspecialchars($vet['name']); ?></div>
            <div class='subtext'>Specialization: <?php echo htmlspecialchars($vet['specialization']); ?></div>
            <div class='subtext'>Clinic: <?php echo htmlspecialchars($vet['clinic_name']); ?></div>
            <div class='subtext'>Location: <?php echo htmlspecialchars($vet['location']); ?></div>
          </div>
          <div class='stats'>
            <div class='small'>📧 <?php echo htmlspecialchars($vet['email']); ?></div>
            <button class='btn' onclick="messageVet('<?php echo htmlspecialchars($vet['name']); ?>')">Contact ✉️</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
function messageVet(name) {
  alert('Contacting ' + name + ' now!');
}

// ---- Sample Veterinary Clinics (replace with database/API) ----
const vetClinics = [
  { name: "Happy Paws Veterinary Clinic", lat: 14.5995, lng: 120.9842 },
  { name: "PetZone Animal Hospital", lat: 14.6102, lng: 121.0087 },
  { name: "VetCare Specialists", lat: 14.5853, lng: 121.0567 },
  { name: "Animal Wellness Clinic", lat: 14.6042, lng: 121.0330 }
];

// ---- Initialize Map ----
const map = L.map("clinicMap").setView([14.5995, 120.9842], 13);

// ---- Add Map Tiles ----
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution: "© OpenStreetMap contributors"
}).addTo(map);

// ---- User Location Detection ----
function detectLocation() {
  if (!navigator.geolocation) {
    document.getElementById("locationStatus").innerText =
      "Geolocation is not supported by your browser.";
    return;
  }

  navigator.geolocation.getCurrentPosition(
    position => {
      const userLat = position.coords.latitude;
      const userLng = position.coords.longitude;

      document.getElementById("locationStatus").innerText =
        "Your location detected. Showing nearby clinics.";

      // Place user location marker
      L.marker([userLat, userLng], { title: "You Are Here" })
        .addTo(map)
        .bindPopup("<b>You are here</b>")
        .openPopup();

      map.setView([userLat, userLng], 15);

      // Compute distance and show nearby clinics
      vetClinics.forEach(clinic => {
        const distance = calcDistance(userLat, userLng, clinic.lat, clinic.lng);

        // Only show clinics within 10 km
        if (distance <= 10) {
          L.marker([clinic.lat, clinic.lng])
            .addTo(map)
            .bindPopup(`<b>${clinic.name}</b><br>${distance.toFixed(2)} km away`);
        }
      });
    },

    error => {
      document.getElementById("locationStatus").innerText =
        "Unable to retrieve your location.";
    }
  );
}

// ---- Haversine Formula for Distance ----
function calcDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // km
  const dLat = (lat2 - lat1) * (Math.PI / 180);
  const dLon = (lon2 - lon1) * (Math.PI / 180);

  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(lat1 * (Math.PI / 180)) *
      Math.cos(lat2 * (Math.PI / 180)) *
      Math.sin(dLon / 2) ** 2;

  return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
}

// ---- Start the widget ----
detectLocation();
</script>
</body>
</html>