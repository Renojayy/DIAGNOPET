<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Diagnopet - Pet Registration</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Pacifico&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(180deg, #e6f5ff, #f9fcff);
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      color: #333;
    }

    header {
      font-family: 'Pacifico', cursive;
      color: #0073e6;
      font-size: 2.2rem;
      margin-bottom: 10px;
    }

    .registration-card {
      background: white;
      width: 100%;
      max-width: 400px;
      border-radius: 20px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
      padding: 30px 25px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .registration-card h2 {
      color: #0073e6;
      margin-bottom: 20px;
    }

    form {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    input, select {
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
      outline: none;
    }

    input:focus, select:focus {
      border-color: #0073e6;
    }

    button {
      background: #0073e6;
      color: white;
      font-weight: 600;
      border: none;
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background: #005bb5;
    }

    .footer-text {
      margin-top: 20px;
      color: #777;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
  <header>🐾 Diagnopet</header>

  <div class="registration-card">
    <h2>Pet Registration</h2>
    <form id="pet-form">
      <input type="text" id="petName" placeholder="Pet Name" required />
      <input type="number" id="petAge" placeholder="Pet Age" required />
      <select id="petSpecies" required>
        <option value="">Select Species</option>
        <option value="Dog">Dog</option>
        <option value="Cat">Cat</option>
        <option value="Other">Other</option>
      </select>
      <input type="text" id="petBreed" placeholder="Pet Breed" />
      <input type="text" id="ownerName" placeholder="Owner Name" required />
      <input type="text" id="contactNumber" placeholder="Contact Number" required />

      <button type="submit">Register Pet</button>
    </form>

    <div class="footer-text">Your pet’s journey to better health starts here 🩵</div>
  </div>

  <script>
    document.getElementById("pet-form").addEventListener("submit", function (e) {
      e.preventDefault();

      const petData = {
        name: document.getElementById("petName").value,
        age: document.getElementById("petAge").value,
        species: document.getElementById("petSpecies").value,
        breed: document.getElementById("petBreed").value,
        owner: document.getElementById("ownerName").value,
        contact: document.getElementById("contactNumber").value,
      };

      localStorage.setItem("petData", JSON.stringify(petData));

      alert("Pet registered successfully!");
      window.location.href = "index.php"; // Redirect to dashboard
    });
  </script>
</body>
</html>
