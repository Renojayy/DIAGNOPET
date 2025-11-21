<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $type = trim($_POST['type']);
    $name = trim($_POST['name']);
    $gender = trim($_POST['gender']);
    $age = trim($_POST['age']);
    $weight = trim($_POST['weight']);
    $breed = trim($_POST['breed']);
    $user = $_SESSION['user_name'];

    // Handle file upload
    $avatar = null;

    if (!empty($_FILES['avatar']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir);
        }

        $fileName = uniqid() . "_" . basename($_FILES["avatar"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
            $avatar = $targetFile;
        }
    }

    // Insert into DB
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Pet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: Inter, system-ui;
            background:#eef2ff;
            margin:0;
            padding:0;
        }
        .wrap{
            max-width:600px;
            margin:40px auto;
            background:#fff;
            padding:30px;
            border-radius:16px;
            box-shadow:0 8px 30px rgba(39,45,90,0.1);
        }
        h2{
            margin-top:0;
            color:#5c4fff;
            text-align:center;
        }
        label{
            font-weight:600;
            display:block;
            margin-top:14px;
        }
        input, select{
            width:100%;
            padding:10px;
            margin-top:6px;
            border-radius:10px;
            border:1px solid #ddd;
        }
        .searchable-select {
            position: relative;
        }
        .searchable-select input {
            cursor: pointer;
            padding-right: 30px; /* Space for arrow */
        }
        .searchable-select::after {
            content: '▼';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #666;
        }
        .dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            width: 100%;
            box-sizing: border-box;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .dropdown-list li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .dropdown-list li:hover {
            background: #f0f0f0;
        }
        .dropdown-list li:last-child {
            border-bottom: none;
        }
        .btn{
            margin-top:20px;
            width:100%;
            padding:12px;
            background:#5c4fff;
            color:#fff;
            border:none;
            border-radius:12px;
            cursor:pointer;
            font-size:16px;
            font-weight:600;
            box-shadow:0 8px 20px rgba(92,79,255,0.2);
        }
        .msg{
            padding:10px;
            color:#fff;
            background:#5c4fff;
            text-align:center;
            border-radius:8px;
            margin-bottom:10px;
        }
        .back{
            margin-top:12px;
            text-align:center;
        }
        .back a{
            color:#5c4fff;
            text-decoration:none;
            font-weight:600;
        }
    </style>
</head>
<body>

<div class="wrap">

    <h2>Add Your Pet</h2>

    <?php if (!empty($msg)): ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php endif; ?>

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
        <div class="searchable-select" id="breedContainer">
            <input type="text" id="breedSearch" placeholder="Search and select breed..." autocomplete="off">
            <input type="hidden" name="breed" id="breedSelect" required>
            <ul id="breedList" class="dropdown-list"></ul>
        </div>

        <label>Symptoms</label>
        <select name="symptoms[]" multiple size="10" style="height: 200px;">
            <optgroup label="🦠 Infectious Disease Symptoms">
                <option value="Fever">Fever</option>
                <option value="Lethargy / Weakness">Lethargy / Weakness</option>
                <option value="Loss of appetite">Loss of appetite</option>
                <option value="Nasal discharge">Nasal discharge</option>
                <option value="Eye discharge">Eye discharge</option>
                <option value="Coughing">Coughing</option>
                <option value="Sneezing">Sneezing</option>
                <option value="Difficulty breathing">Difficulty breathing</option>
                <option value="Swollen lymph nodes">Swollen lymph nodes</option>
                <option value="Sudden weight loss">Sudden weight loss</option>
            </optgroup>
            <optgroup label="🩹 Skin, Coat, and Fur Symptoms">
                <option value="Itching (Pruritus)">Itching (Pruritus)</option>
                <option value="Redness / Inflamed skin">Redness / Inflamed skin</option>
                <option value="Hair loss / Bald patches">Hair loss / Bald patches</option>
                <option value="Scabs or crusty skin">Scabs or crusty skin</option>
                <option value="Hot spots (wet, red sores)">Hot spots (wet, red sores)</option>
                <option value="Greasy coat">Greasy coat</option>
                <option value="Dry / flaky skin">Dry / flaky skin</option>
                <option value="Rash">Rash</option>
                <option value="Skin wounds that won’t heal">Skin wounds that won’t heal</option>
                <option value="Foul skin odor">Foul skin odor</option>
            </optgroup>
            <optgroup label="🦴 Musculoskeletal Symptoms">
                <option value="Limping">Limping</option>
                <option value="Difficulty standing up">Difficulty standing up</option>
                <option value="Stiffness (especially after rest)">Stiffness (especially after rest)</option>
                <option value="Joint pain">Joint pain</option>
                <option value="Muscle wasting">Muscle wasting</option>
                <option value="Weak hind legs">Weak hind legs</option>
                <option value="Trouble jumping or climbing stairs">Trouble jumping or climbing stairs</option>
                <option value="Decreased movement / reluctance to walk">Decreased movement / reluctance to walk</option>
            </optgroup>
            <optgroup label="🧠 Neurological Symptoms">
                <option value="Seizures">Seizures</option>
                <option value="Tremors">Tremors</option>
                <option value="Disorientation">Disorientation</option>
                <option value="Head tilt">Head tilt</option>
                <option value="Circling">Circling</option>
                <option value="Loss of balance">Loss of balance</option>
                <option value="Paralysis (partial or full)">Paralysis (partial or full)</option>
                <option value="Change in behavior">Change in behavior</option>
                <option value="Sensitivity to light or sound">Sensitivity to light or sound</option>
            </optgroup>
            <optgroup label="🫁 Respiratory Symptoms">
                <option value="Persistent cough">Persistent cough</option>
                <option value="Wheezing">Wheezing</option>
                <option value="Fast breathing (tachypnea)">Fast breathing (tachypnea)</option>
                <option value="Labored breathing">Labored breathing</option>
                <option value="Open-mouth breathing (especially cats)">Open-mouth breathing (especially cats)</option>
                <option value="Blue or pale gums">Blue or pale gums</option>
            </optgroup>
            <optgroup label="🫀 Heart & Circulatory Symptoms">
                <option value="Exercise intolerance">Exercise intolerance</option>
                <option value="Fainting / collapsing">Fainting / collapsing</option>
                <option value="Pale gums">Pale gums</option>
                <option value="Fluid buildup (abdomen or chest)">Fluid buildup (abdomen or chest)</option>
                <option value="Irregular heartbeat">Irregular heartbeat</option>
                <option value="Persistent fatigue">Persistent fatigue</option>
            </optgroup>
            <optgroup label="🐾 Ear Symptoms">
                <option value="Ear scratching">Ear scratching</option>
                <option value="Head shaking">Head shaking</option>
                <option value="Ear discharge (brown/yellow)">Ear discharge (brown/yellow)</option>
                <option value="Strong ear odor">Strong ear odor</option>
                <option value="Tilted head">Tilted head</option>
            </optgroup>
            <optgroup label="🍽️ Digestive Symptoms">
                <option value="Vomiting">Vomiting</option>
                <option value="Diarrhea">Diarrhea</option>
                <option value="Constipation">Constipation</option>
                <option value="Blood in stool">Blood in stool</option>
                <option value="Bloating / swollen abdomen">Bloating / swollen abdomen</option>
                <option value="Weight loss">Weight loss</option>
                <option value="Increased thirst">Increased thirst</option>
                <option value="Increased urination">Increased urination</option>
                <option value="Lack of appetite or picky eating">Lack of appetite or picky eating</option>
            </optgroup>
            <optgroup label="💩 Parasitic Symptoms">
                <option value="Scooting">Scooting</option>
                <option value="Visible worms in stool">Visible worms in stool</option>
                <option value="Worm segments near anus">Worm segments near anus</option>
                <option value="Pot-bellied appearance (puppies/kittens)">Pot-bellied appearance (puppies/kittens)</option>
                <option value="Pale gums (from parasites)">Pale gums (from parasites)</option>
            </optgroup>
            <optgroup label="🧬 Endocrine (Hormonal) Symptoms">
                <option value="Excessive thirst">Excessive thirst</option>
                <option value="Excessive urination">Excessive urination</option>
                <option value="Excessive hunger">Excessive hunger</option>
                <option value="Sudden weight gain">Sudden weight gain</option>
                <option value="Sudden weight loss">Sudden weight loss</option>
                <option value="Hair thinning / symmetrical hair loss">Hair thinning / symmetrical hair loss</option>
                <option value="Skin darkening">Skin darkening</option>
            </optgroup>
            <optgroup label="😾 Urinary System Symptoms">
                <option value="Straining to urinate">Straining to urinate</option>
                <option value="Blood in urine">Blood in urine</option>
                <option value="Frequent urination (small amounts)">Frequent urination (small amounts)</option>
                <option value="Painful urination">Painful urination</option>
                <option value="Urinating outside the litterbox (cats)">Urinating outside the litterbox (cats)</option>
                <option value="Blocked urine flow (emergency)">Blocked urine flow (emergency)</option>
            </optgroup>
            <optgroup label="🩸 General & Systemic Symptoms">
                <option value="Swollen belly">Swollen belly</option>
                <option value="Dehydration">Dehydration</option>
                <option value="Collapse">Collapse</option>
                <option value="Weak pulse">Weak pulse</option>
                <option value="Shaking / chills">Shaking / chills</option>
                <option value="Depression / no interest in play">Depression / no interest in play</option>
                <option value="Gums turning yellow (jaundice)">Gums turning yellow (jaundice)</option>
                <option value="Vomiting foam">Vomiting foam</option>
                <option value="Sudden aggressive or unusual behavior">Sudden aggressive or unusual behavior</option>
            </optgroup>
        </select>

        <label>Upload Photo</label>
        <input type="file" name="avatar" accept="image/*">

        <button class="btn" type="submit">Save Pet</button>
    </form>

    <div class="back">
        <a href="petowner_dashboard.php">← Back to Dashboard</a>
    </div>

</div>

<script>
    const dogBreeds = [
        "Aspin / Askal", "Labrador Retriever", "Golden Retriever", "Shih Tzu", "Pomeranian", "Poodle", "Chihuahua", "Pug", "Siberian Husky", "German Shepherd", "Beagle", "Dachshund", "French Bulldog", "Maltese", "Boxer", "Rottweiler", "Cocker Spaniel", "Yorkshire Terrier", "Shiba Inu", "Border Collie", "Australian Shepherd", "Jack Russell Terrier", "Doberman Pinscher", "Great Dane", "Corgi", "Miniature Pinscher", "Bichon Frise", "Bull Terrier", "Cavalier King Charles Spaniel", "Belgian Malinois", "Chow Chow", "Alaskan Malamute", "Basenji", "Cane Corso", "English Springer Spaniel", "Irish Setter", "Havanese", "Shar Pei", "Lhasa Apso", "American Bully", "Pit Bull Terrier", "Bullmastiff", "Samoyed", "Tibetan Terrier", "American Eskimo Dog", "Old English Sheepdog", "Dalmatian", "Whippet", "Greyhound", "Akita"
    ];

    const catBreeds = [
        "Puspin", "Domestic Shorthair", "Persian", "Siamese", "Maine Coon", "Ragdoll", "Bengal", "British Shorthair", "Scottish Fold", "Sphynx", "Norwegian Forest", "Russian Blue", "Exotic Shorthair", "Burmese", "Himalayan", "Abyssinian", "Oriental Shorthair", "Turkish Angora", "Tonkinese", "Birman", "Savannah", "Cornish Rex", "Devon Rex", "Egyptian Mau", "Manx", "Ragamuffin", "Chartreux", "Balinese", "Japanese Bobtail", "American Shorthair", "Australian Mist", "LaPerm", "Singapura", "Selkirk Rex", "Siberian", "Ocicat", "Serengeti", "Pixiebob", "Khao Manee", "Korat", "Lykoi", "Peterbald", "Chausie", "Turkish Van", "American Bobtail", "Brazilian Shorthair", "California Spangled", "Oriental Longhair", "Cymric", "Burmilla"
    ];

    let currentBreeds = [];

    function populateBreeds(list, searchTerm = '') {
        const breedList = document.getElementById('breedList');
        breedList.innerHTML = ""; // Clear

        const filteredList = list.filter(breed => breed.toLowerCase().includes(searchTerm.toLowerCase()));

        filteredList.forEach(b => {
            const li = document.createElement("li");
            li.textContent = b;
            li.addEventListener('click', function() {
                document.getElementById('breedSearch').value = b;
                document.getElementById('breedSelect').value = b;
                breedList.style.display = 'none';
            });
            breedList.appendChild(li);
        });
    }

    document.getElementById('typeSelect').addEventListener('change', function() {
        const type = this.value;
        const searchInput = document.getElementById('breedSearch');
        const breedList = document.getElementById('breedList');
        searchInput.value = ''; // Reset search
        document.getElementById('breedSelect').value = '';

        if (type === "Dog") {
            currentBreeds = dogBreeds;
        } else if (type === "Cat") {
            currentBreeds = catBreeds;
        } else {
            currentBreeds = [];
            breedList.innerHTML = '';
            return;
        }

        populateBreeds(currentBreeds);
    });

    document.getElementById('breedSearch').addEventListener('focus', function() {
        if (currentBreeds.length > 0) {
            document.getElementById('breedList').style.display = 'block';
        }
    });

    document.getElementById('breedSearch').addEventListener('blur', function() {
        setTimeout(() => {
            document.getElementById('breedList').style.display = 'none';
        }, 150); // Delay to allow click on li
    });

    document.getElementById('breedSearch').addEventListener('input', function() {
        const searchTerm = this.value;
        if (currentBreeds.length > 0) {
            populateBreeds(currentBreeds, searchTerm);
            document.getElementById('breedList').style.display = 'block';
        }
    });
</script>

</body>
</html>
