<?php
session_start();
include 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

// Define dog and cat breeds
$dog_breeds = [
    "Aspin / Askal", "Labrador Retriever", "Golden Retriever", "Shih Tzu",
    "Pomeranian", "Poodle", "Chihuahua", "Pug", "Siberian Husky",
    "German Shepherd", "Beagle", "Dachshund", "French Bulldog",
    "Maltese", "Boxer", "Rottweiler", "Cocker Spaniel",
    "Yorkshire Terrier", "Shiba Inu", "Border Collie",
    "Australian Shepherd", "Jack Russell Terrier", "Doberman Pinscher",
    "Great Dane", "Corgi", "Miniature Pinscher", "Bichon Frise",
    "Bull Terrier", "Cavalier King Charles Spaniel", "Belgian Malinois",
    "Chow Chow", "Alaskan Malamute", "Basenji", "Cane Corso",
    "English Springer Spaniel", "Irish Setter", "Havanese", "Shar Pei",
    "Lhasa Apso", "American Bully", "Pit Bull Terrier", "Bullmastiff",
    "Samoyed", "Tibetan Terrier", "American Eskimo Dog",
    "Old English Sheepdog", "Dalmatian", "Whippet", "Greyhound", "Akita"
];

$cat_breeds = [
    "Puspin", "Domestic Shorthair", "Persian", "Siamese", "Maine Coon",
    "Ragdoll", "Bengal", "British Shorthair", "Scottish Fold",
    "Sphynx", "Norwegian Forest", "Russian Blue", "Exotic Shorthair",
    "Burmese", "Himalayan", "Abyssinian", "Oriental Shorthair",
    "Turkish Angora", "Tonkinese", "Birman", "Savannah", "Cornish Rex",
    "Devon Rex", "Egyptian Mau", "Manx", "Ragamuffin", "Chartreux",
    "Balinese", "Japanese Bobtail", "American Shorthair",
    "Australian Mist", "LaPerm", "Singapura", "Selkirk Rex",
    "Siberian", "Ocicat", "Serengeti", "Pixiebob", "Khao Manee",
    "Korat", "Lykoi", "Peterbald", "Chausie", "Turkish Van",
    "American Bobtail", "Brazilian Shorthair", "California Spangled",
    "Oriental Longhair", "Cymric", "Burmilla"
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = trim($_POST['type']);
    $name = trim($_POST['name']);
    $gender = trim($_POST['gender']);
    $age = trim($_POST['age']);
    $weight = trim($_POST['weight']);
    $breed = trim($_POST['breed']);
    $user = $_SESSION['user_name'];

    // Validate breed
    if ($type === 'Dog' && !in_array($breed, $dog_breeds)) {
        $msg = "Invalid dog breed selected.";
    } elseif ($type === 'Cat' && !in_array($breed, $cat_breeds)) {
        $msg = "Invalid cat breed selected.";
    } else {
        // Handle file upload
        $avatar = null;
        if (!empty($_FILES['avatar']['name'])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir);
            $fileName = uniqid() . "_" . basename($_FILES["avatar"]["name"]);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
                $avatar = $targetFile;
            }
        }

        // Insert pet into database
        $sql = "INSERT INTO pets (`Pet Type`, `Pet Name`, `Pet Gender`, `Pet Weight`, `Pet Breed`, `Pet Age`, user_name, avatar)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $type, $name, $gender, $weight, $breed, $age, $user, $avatar);

        if ($stmt->execute()) {
            $pet_id = $conn->insert_id;

            // Insert symptoms if provided
            if (!empty($_POST['symptoms'])) {
                foreach ($_POST['symptoms'] as $symp) {
                    $symp = trim($symp);
                    if (!empty($symp)) {
                        $sql_sym = "INSERT INTO symptoms (pet_id, symptom, user_name) VALUES (?, ?, ?)";
                        $stmt_sym = $conn->prepare($sql_sym);
                        $stmt_sym->bind_param("iss", $pet_id, $symp, $user);
                        $stmt_sym->execute();
                    }
                }
            }

            $msg = "Pet added successfully!";
            header("Location: petowner_dashboard.php");
            exit();
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Pet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family: Inter, system-ui; background:#eef2ff; margin:0; padding:0;}
        .wrap{max-width:600px;margin:40px auto;background:#fff;padding:30px;border-radius:16px;box-shadow:0 8px 30px rgba(39,45,90,0.1);}
        h2{text-align:center;color:#5c4fff;}
        label{font-weight:600;display:block;margin-top:14px;}
        input, select{width:100%;padding:10px;margin-top:6px;border-radius:10px;border:1px solid #ddd;}
        .btn{margin-top:20px;width:100%;padding:12px;background:#5c4fff;color:#fff;border:none;border-radius:12px;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 8px 20px rgba(92,79,255,0.2);}
        .msg{padding:10px;color:#fff;background:#5c4fff;text-align:center;border-radius:8px;margin-bottom:10px;}
        .back{text-align:center;margin-top:12px;}
        .back a{color:#5c4fff;text-decoration:none;font-weight:600;}
    </style>
</head>
<body>
<div class="wrap">
    <h2>Add Your Pet</h2>
    <?php if (!empty($msg)) echo "<div class='msg'>{$msg}</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Type</label>
        <select name="type" id="typeSelect" required>
            <option value="">Select Type</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
        </select>

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Gender</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label>Age</label>
        <input type="text" name="age" placeholder="e.g. 2 years" required>

        <label>Weight (kg)</label>
        <input type="number" step="0.1" name="weight" placeholder="e.g. 5.3" required>

        <label>Breed</label>
        <input type="text" name="breed" placeholder="Enter breed" required>

        <label>Symptoms (Ctrl+Click to select multiple)</label>
        <select name="symptoms[]" multiple size="10">
            <!-- Add your symptom options here as in previous code -->
        </select>

        <label>Upload Photo</label>
        <input type="file" name="avatar" accept="image/*">

        <button class="btn" type="submit">Save Pet</button>
    </form>

    <div class="back"><a href="petowner_dashboard.php">← Back to Dashboard</a></div>
</div>
</body>
</html>
