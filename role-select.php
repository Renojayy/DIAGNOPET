<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Role - Diagnopet</title>
  <style>
    body {
      font-family: 'Poppins', Georgia;
      margin: 0;
      padding: 0;
      background: linear-gradient(180deg, #f8fbff 0%, #e8f2ff 100%);
      color: #003366;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
    }

    .container {
      text-align: center;
      z-index: 2;
    }

    h2 {
      color: #0055cc;
      font-size: 2.2em;
      margin-bottom: 10px;
    }

    p {
      color: #336699;
      margin-bottom: 40px;
      font-size: 1.1em;
    }

    .cards {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      width: 260px;
      padding: 40px 25px;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 3px solid transparent;
      text-align: center;
    }

    .card:hover {
      transform: translateY(-8px);
      border-color: #007bff;
      box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
    }

    .icon {
      font-size: 60px;
      margin-bottom: 20px;
    }

    .card h3 {
      font-size: 1.5em;
      color: #0055cc;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 1em;
      color: #336699;
      margin: 0;
    }

    .back {
      margin-top: 30px;
      display: inline-block;
      color: #336699;
      text-decoration: underline;
      cursor: pointer;
      font-size: 0.95em;
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
      50% { transform: translateY(-25px) scale(1.05); }
      100% { transform: translateY(0) scale(1); }
    }

    .bubble:nth-child(1) { width:120px; height:120px; top:15%; left:20%; animation-delay:0s; }
    .bubble:nth-child(2) { width:80px; height:80px; top:70%; left:70%; animation-delay:2s; }
    .bubble:nth-child(3) { width:100px; height:100px; top:50%; left:45%; animation-delay:4s; }
    .bubble:nth-child(4) { width:60px; height:60px; top:80%; left:30%; animation-delay:1s; }

    @media (max-width: 600px) {
      .cards {
        flex-direction: column;
        gap: 20px;
      }
      .card {
        width: 80%;
      }
    }
  </style>
</head>
<body>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>

  <div class="container">
    <h2>Select Your Role</h2>
    <p>Choose how you’ll use Diagnopet</p>

    <div class="cards">
      <div class="card" onclick="selectRole('vet')">
        <div class="icon">👩‍⚕️</div>
        <h3>Veterinarian</h3>
        <p>Access pet health data, insights, and diagnostics</p>
      </div>

      <div class="card" onclick="selectRole('owner')">
        <div class="icon">🐾</div>
        <h3>Pet Owner</h3>
        <p>Check your pet’s health and connect with vets</p>
      </div>
    </div>

    <div class="back" onclick="goBack()">← Back to Home</div>
  </div>

  <script>
    function selectRole(role) {
      localStorage.setItem('userRole', role);
      if (role === 'vet') {
        window.location.href = 'vetlanding.php';
      } else {
        window.location.href = 'petowner_dashboard.php';
      }
    }

    function goBack() {
      window.location.href = 'index.php';
    }
  </script>
</body>
</html>
