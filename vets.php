<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Vet Profiles</title>
<style>
  body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f5f6f8;
    padding: 20px 0;
  }
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
  }
  .header h1 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
  }
  .back-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
  }
  .main-content {
    display: flex;
    padding: 0 20px;
    gap: 20px;
  }
  .sidebar {
    width: 200px;
    background: white;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: fit-content;
  }
  .sidebar h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
  }
  .sidebar ul { list-style: none; padding: 0; }
  .sidebar li { margin-bottom: 10px; }
  .sidebar a { text-decoration: none; color: #333; font-size: 16px; }
  .sidebar a:hover { color: #007bff; }

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

  /* Responsive */
  @media(max-width: 900px) {
    .main-content { flex-direction: column; align-items: center; }
    .sidebar { width: 90%; }
  }
  @media(max-width: 768px) {
    .card { width: 90%; }
  }
</style>
</head>
<body>

<div class="header">
  <h1>Veterinarians in Diagnopet</h1>
  <button class="back-btn" onclick="window.history.back()">Back</button>
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
  </div>

  <div class="container">
    <?php
      $vets = [
        ["name" => "Dr. Maria Santos", "img" => "https://via.placeholder.com/600x600", "specialization" => "Small Animals / Surgery", "active" => true, "hours" => "9:00 AM - 6:00 PM", "clients" => 512, "rating" => 4.8],
        ["name" => "Dr. Paulo Ramirez", "img" => "https://via.placeholder.com/600x600", "specialization" => "Exotic Pets / Dental Care", "active" => false, "hours" => "10:00 AM - 5:00 PM", "clients" => 304, "rating" => 4.2],
        ["name" => "Dr. Hannah Villareal", "img" => "https://via.placeholder.com/600x600", "specialization" => "Farm Animals / Vaccination", "active" => true, "hours" => "7:00 AM - 3:00 PM", "clients" => 428, "rating" => 4.9],
      ];

      foreach ($vets as $vet) {
        echo "
        <div class='card'>
          <img src='https://img.icons8.com/ios-filled/96/000000/user.png' alt='User Silhouette' class='placeholder-icon'>
          <div class='content'>
            <div class='name'>{$vet['name']}" . ($vet['active'] ? " <span class='green-dot'></span>" : "") . "</div>
            <div class='rating'>" . str_repeat("⭐", floor($vet['rating'])) . " {$vet['rating']}</div>
            <div class='subtext'>Specialization: {$vet['specialization']}</div>
            <div class='subtext'>Availability: {$vet['hours']}</div>
          </div>
          <div class='stats'>
            <div class='small'>👥 {$vet['clients']}</div>
            <button class='btn' onclick=\"messageVet('{$vet['name']}')\">Message Now ✉️</button>
          </div>
        </div>
        ";
      }
    ?>
  </div>
</div>

<script>
function messageVet(name) {
  alert('Messaging ' + name + ' now!');
}
</script>
</body>
</html>