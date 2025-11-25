<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $name = $first_name . ' ' . $last_name;

    $specialization = null;
    if (isset($_POST['specialization']) && is_array($_POST['specialization'])) {
        $specialization = implode(", ", array_map('trim', $_POST['specialization']));
    } elseif (isset($_POST['specialization'])) {
        $specialization = trim($_POST['specialization']);
    }

    $license_number = trim($_POST['license_number']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = trim($_POST['email']);
    $clinic_address = trim($_POST['clinic_address']);
    $city = trim($_POST['city']);
    $clinic_name = trim($_POST['clinic_name']);
    $expiration_date = isset($_POST['expiration_date']) ? trim($_POST['expiration_date']) : null;

    // Get latitude and longitude from form
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;

    $file_path = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['attachment']['tmp_name'];
        $file_name = basename($_FILES['attachment']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_file_ext = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx');
        if (in_array($file_ext, $allowed_file_ext)) {
            $target_dir = "uploads/images/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $sanitized_name = strtolower($name);
            $sanitized_name = preg_replace('/\s+/', '_', $sanitized_name);
            $sanitized_name = preg_replace('/[^a-z0-9_\-]/', '', $sanitized_name);
            $file_path = $target_dir . $sanitized_name . '.' . $file_ext;

            if (!move_uploaded_file($file_tmp_name, $file_path)) {
                $error = "Failed to upload attachment.";
            }
        } else {
            $error = "Invalid file type. Allowed types: jpg, jpeg, png, gif, pdf, doc, docx, txt, xls, xlsx.";
        }
    }

    if (empty($first_name) || empty($last_name) || empty($specialization) || empty($license_number) || empty($password) || empty($email) || empty($clinic_address) || empty($city) || empty($clinic_name) || empty($expiration_date)) {
        $error = "All fields are required including expiration date.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!isset($error)) {
        $stmt = $conn->prepare("SELECT id FROM veterinarian WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO veterinarian 
                (name, specialization, license_number, password, email, clinic_address, city, clinic_name, expiration_date, prc_id_path, status, latitude, longitude) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $status = 'pending';
            $stmt->bind_param("sssssssssssss", $name, $specialization, $license_number, $password, $email, $clinic_address, $city, $clinic_name, $expiration_date, $file_path, $status, $latitude, $longitude);
            if ($stmt->execute()) {
                header("Location: vet-review-wait.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vet Registration - Diagnopet</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="style.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#3B82F6',
            secondary: '#10B981',
            accent: '#F59E0B'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4 overflow-auto">
  <!-- Floating decorative elements -->
  <div class="absolute w-32 h-32 rounded-full bg-primary/10 top-1/4 left-1/5 animate-float"></div>
  <div class="absolute w-24 h-24 rounded-full bg-secondary/10 bottom-1/4 right-1/5 animate-float-delay"></div>
  <div class="absolute w-20 h-20 rounded-full bg-accent/10 top-1/3 right-1/4 animate-float-delay-2"></div>
  <div class="absolute w-16 h-16 rounded-full bg-primary/10 bottom-1/3 left-1/4 animate-float"></div>

  <!-- Main card -->
  <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative z-10 backdrop-blur-sm bg-white/90">
    <div class="text-center mb-8">
      <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <i data-feather="user-plus" class="text-primary w-8 h-8"></i>
      </div>
      <h2 class="text-3xl font-bold text-gray-800 mb-2">Vet Registration</h2>
      <p class="text-gray-600">Join Diagnopet now</p>
      <?php if (isset($error)) echo "<p class='text-red-500'>$error</p>"; ?>
    </div>

<form id="vetForm" action="vet-register.php" method="POST" enctype="multipart/form-data" class="space-y-4">
      <div class="relative flex gap-4">
        <div class="flex-1 relative">
          <i data-feather="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input type="text" name="first_name" id="first_name" placeholder="First Name" required
            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
        </div>
        <div class="flex-1 relative">
          <i data-feather="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input type="text" name="last_name" id="last_name" placeholder="Last Name" required
            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
        </div>
      </div>

      <div class="relative">
        <label for="specialization_type" class="block mb-1 font-medium text-gray-700">Specialization Type</label>
        <select name="specialization_type" id="specialization_type" required
          class="w-full pl-3 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
          <option value="">Select an option</option>
          <option value="general">General Specializations</option>
          <option value="advanced">Advanced Medical Specialties</option>
          <option value="both">Both</option>
        </select>
      </div>

      <div id="specializations_container" class="mb-4 hidden">
        <label class="block mb-1 font-medium text-gray-700">Select Specializations</label>
        <div id="specializations_list" class="grid grid-cols-1 gap-2 max-h-56 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-white"></div>
      </div>

      <div class="relative">
        <i data-feather="file-text" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="license_number" id="license_number" placeholder="License Number" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <!-- Removed Verification Status choice -->

      <div class="relative">
        <label for="expiration_date" class="block text-gray-700 font-medium mb-1">Expiration Date</label>
        <input type="date" name="expiration_date" id="expiration_date" required
          class="w-full pl-3 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      
      <!-- Removed image upload input field -->

      <div class="relative">
        <label for="attachment" class="block text-gray-700 font-medium mb-1">UPLOAD PRC ID</label>
        <input type="file" name="attachment" id="attachment" accept="image/*"
          class="w-full border border-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="password" name="password" id="password" placeholder="Password" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="mail" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="email" name="email" id="email" placeholder="Email" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="map-pin" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="clinic_address" id="clinic_address" placeholder="Clinic Address" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="map-pin" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="city" id="city" placeholder="City" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="home" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="clinic_name" id="clinic_name" placeholder="Clinic Name" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">


      <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
        <i data-feather="user-plus" class="w-5 h-5"></i>
        Register Account
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="vetlanding.php" class="text-gray-600 hover:text-primary transition flex items-center justify-center gap-1">
        <i data-feather="arrow-left" class="w-4 h-4"></i>
        Back to previous page
      </a>
<p>
    Already have an Account? 
    <a href="vet-login.php" style="color: blue;">Click Here</a>
</p>
    </div>
  </div>

  <script>
    feather.replace();

    (() => {
      const specTypeSelect = document.getElementById('specialization_type');
      const specContainer = document.getElementById('specializations_container');
      const specListDiv = document.getElementById('specializations_list');

      const generalSpecs = [
        "Companion Animal Practice",
        "Canine and Feline Practice",
        "Feline Practice"
      ];

      const advancedSpecs = [
        "Anesthesia and Analgesia",
        "Cardiology",
        "Dermatology",
        "Emergency and Critical Care",
        "Internal Medicine",
        "Neurology",
        "Nutrition",
        "Oncology",
        "Ophthalmology",
        "Radiology/Diagnostic Imaging",
        "Surgery",
        "Veterinary Behavior"
      ];

      function renderCheckboxes(items) {
        specListDiv.innerHTML = "";
        items.forEach((item, idx) => {
          const checkboxId = "spec_" + idx;
          const wrapper = document.createElement("div");
          wrapper.className = "flex items-center";

          const checkbox = document.createElement("input");
          checkbox.type = "checkbox";
          checkbox.name = "specialization[]";
          checkbox.id = checkboxId;
          checkbox.value = item;
          checkbox.className = "mr-2";

          const label = document.createElement("label");
          label.htmlFor = checkboxId;
          label.textContent = item;
          label.className = "select-none";

          wrapper.appendChild(checkbox);
          wrapper.appendChild(label);
          specListDiv.appendChild(wrapper);
        });
      }

      specTypeSelect.addEventListener('change', () => {
        const val = specTypeSelect.value;
        if (!val) {
          specContainer.classList.add("hidden");
          specListDiv.innerHTML = "";
          return;
        }
        specContainer.classList.remove("hidden");

        if (val === "general") {
          renderCheckboxes(generalSpecs);
        } else if (val === "advanced") {
          renderCheckboxes(advancedSpecs);
        } else if (val === "both") {
          const allSpecs = [...generalSpecs, ...advancedSpecs];
          renderCheckboxes(allSpecs);
        }
      });
    })();

    // Auto-fill latitude & longitude
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
    }, function(error) {
        console.warn("Geolocation error: ", error.message);
    });
} else {
    console.warn("Geolocation is not supported by this browser.");
}

  </script>
</body>
</html>