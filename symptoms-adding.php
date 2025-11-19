<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$msg = "";
$pet_id = isset($_GET['pet_id']) ? (int)$_GET['pet_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['symptom'])) {
    $user = $_SESSION['user_name'];

    if (!empty($_POST['symptom']) && $pet_id > 0) {
        foreach ($_POST['symptom'] as $symptom) {
            $symptom = trim($symptom);
            if (!empty($symptom)) {
                $sql = "INSERT INTO symptoms (pet_id, symptom, user_name) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $pet_id, $symptom, $user);
                $stmt->execute();
            }
        }
        $msg = "Symptoms added successfully!";
        header("Location: petowner_dashboard.php");
        exit();
    } else {
        $msg = "Please select at least one symptom.";
    }
}

// Fetch pet name for display
$pet_name = "";
if ($pet_id > 0) {
    $sql = "SELECT `Pet Name` FROM pets WHERE pet_id = ? AND user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $pet_id, $_SESSION['user_name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $pet_name = $row['Pet Name'];
    } else {
        $msg = "Pet not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Symptom</title>
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
        input{
            width:100%;
            padding:10px;
            margin-top:6px;
            border-radius:10px;
            border:1px solid #ddd;
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
    <h2>Add Symptom for <?php echo htmlspecialchars($pet_name); ?></h2>

    <?php if (!empty($msg)): ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Symptoms</label>
        <select name="symptom[]" multiple size="10" style="height: 200px;">
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

        <button class="btn" type="submit">Add Symptoms</button>
    </form>

    <div class="back">
        <a href="petowner_dashboard.php">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
