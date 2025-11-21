<?php
session_start();
include 'db_connect.php';
// pet_dashboard.php - Single-file PHP dashboard demo
// Place this file in your XAMPP htdocs (or equivalent) and open in the browser.

// Start session and set random user if not set
if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = 'User' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
}
$user_name = $_SESSION['user_name'];

// Handle delete pet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_pet_id'])) {
    $delete_id = $_POST['delete_pet_id'];
    $user = $_SESSION['user_name'];
    $sql = "DELETE FROM pets WHERE pet_id = ? AND user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $delete_id, $user);
    $stmt->execute();
    header("Location: petowner_dashboard.php");
    exit();
}

// Handle delete symptom
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_symptom_id'])) {
    $delete_id = $_POST['delete_symptom_id'];
    $user = $_SESSION['user_name'];
    $sql = "DELETE s FROM symptoms s JOIN pets p ON s.pet_id = p.pet_id WHERE s.id = ? AND p.user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $delete_id, $user);
    $stmt->execute();
    header("Location: petowner_dashboard.php");
    exit();
}

// Fetch all pets for the user
$pets = [];
if (isset($_SESSION['user_name'])) {
    $user = $_SESSION['user_name'];
    $sql = "SELECT `Pet Name`, `Pet Breed`, `Pet Age`, `Pet Weight`, pet_id, avatar FROM pets WHERE user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pets[] = [
            'name' => $row['Pet Name'],
            'breed' => $row['Pet Breed'],
            'age' => $row['Pet Age'],
            'weight' => $row['Pet Weight'],
            'id' => $row['pet_id'],
            'avatar' => $row['avatar']
        ];
    }
}

// Fetch symptoms for the user grouped by pet
$symptoms_by_pet = [];
$symptoms = [];
if (isset($_SESSION['user_name'])) {
    $user = $_SESSION['user_name'];
    $sql = "SELECT s.id, s.symptom, s.date_added, p.`Pet Name` as pet_name, p.pet_id FROM symptoms s JOIN pets p ON s.pet_id = p.pet_id WHERE p.user_name = ? ORDER BY p.`Pet Name`, s.date_added DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pet_id = $row['pet_id'];
        if (!isset($symptoms_by_pet[$pet_id])) {
            $symptoms_by_pet[$pet_id] = [
                'pet_name' => $row['pet_name'],
                'symptoms' => []
            ];
        }
        $symptoms_by_pet[$pet_id]['symptoms'][] = [
            'id' => $row['id'],
            'symptom' => $row['symptom'],
            'date_added' => $row['date_added']
        ];
        $symptoms[] = [
            'id' => $row['id'],
            'symptom' => $row['symptom'],
            'date_added' => $row['date_added'],
            'pet_name' => $row['pet_name']
        ];
    }
}

// Pagination for symptoms
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 5;
$total_symptoms = count($symptoms);
$total_pages = ceil($total_symptoms / $per_page);
$offset = ($page - 1) * $per_page;
$paginated_symptoms = array_slice($symptoms, $offset, $per_page);

$docs = [];

$appointment = [
    'date' => '',
    'time' => '',
    'vet'  => '',
    'place'=> ''
];

$meds = [];

$events = [];

// Load dog and cat breeds
$dog_breeds = [];
$cat_breeds = [];
$breed_file = 'dog breeds and cat breeds.txt';
if (file_exists($breed_file)) {
    $content = file_get_contents($breed_file);
    $lines = explode("\n", $content);
    $current_section = '';
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, 'dogs:') === 0) {
            $current_section = 'dogs';
        } elseif (strpos($line, 'cats:') === 0) {
            $current_section = 'cats';
        } elseif (!empty($line) && $current_section == 'dogs') {
            $breeds = explode(',', $line);
            foreach ($breeds as $breed) {
                $dog_breeds[] = trim($breed, '"');
            }
        } elseif (!empty($line) && $current_section == 'cats') {
            $breeds = explode(',', $line);
            foreach ($breeds as $breed) {
                $cat_breeds[] = trim($breed, '"');
            }
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DIAGNOPET - Pet Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#f3f6ff; --card:#ffffff; --muted:#8b8fb1; --accent:#5c4fff; --glass: rgba(255,255,255,0.7);
      --shadow: 0 6px 18px rgba(36,41,90,0.08);
    }
    *{box-sizing:border-box}
    body{font-family:Inter,system-ui,Arial; margin:0; background: linear-gradient(180deg,#eef2ff 0%, #f7f9ff 100%); color:#222}
    .wrap{max-width:1200px; margin:36px auto; padding:26px; background:linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.95)); border-radius:14px; box-shadow: 0 8px 30px rgba(39,45,90,0.06); display:grid; grid-template-columns:84px 1fr; gap:18px}
    /* Sidebar */
    .sidebar{padding:18px; display:flex; flex-direction:column; gap:18px; align-items:center}
    .logo{width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#7b6bff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;box-shadow:var(--shadow)}
    .nav{display:flex;flex-direction:column;gap:16px;margin-top:6px}
    .nav button{width:48px;height:48px;border-radius:12px;border:none;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer}
    .nav button.active{background:rgba(92,79,255,0.08)}
    .logout{margin-top:auto;opacity:0.7}

    /* Main */
    .main{padding:8px}
    .header{display:flex;align-items:center;justify-content:space-between;padding:8px 10px}
    .search{display:flex;gap:12px;align-items:center}
    .avatar{display:flex;gap:10px;align-items:center}
    .avatar img{width:36px;height:36px;border-radius:50%}
    .btn-add{background:var(--accent); color:#fff;padding:10px 14px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 8px 20px rgba(92,79,255,0.18)}

    /* content grid */
    .grid{display:grid;grid-template-columns:repeat(12,1fr);gap:16px}
    .card{background:var(--card); border-radius:12px; padding:18px; box-shadow:var(--shadow)}
    .card.col-span-4{grid-column:span 4}
    .card.col-span-5{grid-column:span 5}
    .card.col-span-3{grid-column:span 3}
    .small{font-size:13px;color:var(--muted)}

    /* pet card */
    .pet-card{display:flex;align-items:center;gap:12px}
    .pet-avatar{width:72px;height:72px;border-radius:14px;background:#f1f3ff;display:flex;align-items:center;justify-content:center;font-weight:600}
    .pet-meta{line-height:1}
    .pet-meta h3{margin:0}
    .meta-muted{font-size:13px;color:#6b6f88}

    /* docs list */
    .doc{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3fb}
    .doc:last-child{border-bottom:none}

    /* weight sparkline */
    .spark{height:90px}
    .weight-value{background:#fff;padding:6px 10px;border-radius:14px;display:inline-block;margin-top:6px}

    /* medications */
    .med{padding:8px 0;border-bottom:1px dashed #f1f3fb}
    .med:last-child{border-bottom:none}

    /* coming events */
    .event{padding:10px;border-radius:10px;background:linear-gradient(180deg,#fff,#fcfcff);margin-bottom:10px}

    /* tabs */
    .tabs{display:flex;border-bottom:1px solid #f1f3fb;margin-bottom:16px}
    .tab-button{padding:8px 16px;border:none;background:none;color:#8b8fb1;cursor:pointer;border-bottom:2px solid transparent}
    .tab-button.active{color:#5c4fff;border-bottom-color:#5c4fff;font-weight:600}
    .tab-content{display:none}
    .tab-content.active{display:block}

    /* responsive */
    @media(max-width:980px){.wrap{grid-template-columns:64px 1fr;padding:12px}.grid{grid-template-columns:repeat(6,1fr)}.card.col-span-4{grid-column:span 6}.card.col-span-5{grid-column:span 6}}
    @media(max-width:768px){.wrap{grid-template-columns:56px 1fr;padding:10px}.grid{grid-template-columns:repeat(4,1fr);gap:12px}.card.col-span-4{grid-column:span 4}.card.col-span-5{grid-column:span 4}.card.col-span-3{grid-column:span 4}.sidebar .nav button{width:40px;height:40px}.logo{width:40px;height:40px}.btn-add{padding:8px 12px;font-size:14px}.header{font-size:14px}.pet-avatar{width:60px;height:60px}.spark{height:70px}}
    @media(max-width:480px){.wrap{grid-template-columns:1fr;padding:8px}.sidebar{display:none}.grid{grid-template-columns:1fr;gap:10px}.card{padding:12px}.header{flex-direction:column;gap:8px;text-align:center}.btn-add{width:100%;margin-top:8px}.pet-card{flex-direction:column;text-align:center}.pet-avatar{margin:0 auto}.spark{height:60px}.weight-value{margin-top:4px}.small{font-size:12px}h2{font-size:18px}h3{font-size:16px}h4{font-size:14px}}

    /* Chat Widget Styles */
    #artemis-bubble {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      background: var(--accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: var(--shadow);
      z-index: 1000;
      font-size: 24px;
    }
    #artemis-chatbox {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 300px;
      height: 400px;
      background: var(--card);
      border-radius: 12px;
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      z-index: 1000;
    }
    #artemis-chatbox.hidden {
      display: none;
    }
    .chat-header {
      padding: 10px;
      background: var(--accent);
      color: white;
      border-radius: 12px 12px 0 0;
      font-weight: 600;
    }
    .chat-body {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
    }
    .chat-message {
      margin-bottom: 10px;
      padding: 8px 12px;
      border-radius: 8px;
      max-width: 80%;
    }
    .chat-message.user {
      background: var(--accent);
      color: white;
      align-self: flex-end;
      margin-left: auto;
    }
    .chat-message.bot {
      background: #f1f3fb;
      color: #222;
    }
    .chat-input {
      display: flex;
      padding: 10px;
      border-top: 1px solid #f1f3fb;
    }
    .chat-input input {
      flex: 1;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-right: 8px;
    }
    .chat-input button {
      padding: 8px 12px;
      background: var(--accent);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <aside class="sidebar">
      <div class="logo">DP</div>
      <nav class="nav">
        <button class="active" title="Pets" onclick="navigate('Pets')">🐾</button>
        <button title="Vets" onclick="navigate('Vets')">🏥</button>
        <button title="Profile" onclick="navigate('Profile')">👤</button>
        <button title="Settings" onclick="navigate('Settings')">⚙️</button>
        <button title="Support" onclick="navigate('Support')">💬</button>
      </nav>
      <div class="logout" onclick="goBack()">←</div>
    </aside>

    <main class="main">
      <div class="header">
        <div style="display:flex;align-items:center;gap:18px">
          <h2 style="margin:0;color:var(--accent)">DIAGNOPET</h2>
          <div class="small">Manage your pet's health</div>
        </div>
        <div style="display:flex;gap:12px;align-items:center">
          <button class="btn-add" onclick="window.location.href='pet-adding.php'">Add Pet</button>
          <div class="avatar"><div style="font-size:14px"><?php echo htmlspecialchars($user_name); ?></div></div>
        </div>
      </div>

      <section class="grid" style="margin-top:16px">
        <!-- left column: pets list -->
        <div class="card col-span-4">
          <h4 style="margin:0 0 12px 0">Your Pets</h4>
          <?php if (empty($pets)): ?>
            <div class="pet-card" onclick="window.location.href='pet-adding.php'" style="cursor:pointer;">
              <div class="pet-avatar">➕</div>
              <div class="pet-meta">
                <h3 style="color:#8b8fb1">Add your first pet</h3>
                <div class="meta-muted">Click to add name, breed, age, and more</div>
              </div>
            </div>
          <?php else: ?>
            <?php foreach($pets as $pet): ?>
              <div class="pet-card">
                <div class="pet-avatar">
                  <?php if (!empty($pet['avatar'])): ?>
                    <img src="<?php echo htmlspecialchars($pet['avatar']); ?>" alt="Pet Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">
                  <?php else: ?>
                    <?php
                    $breed = $pet['breed'];
                    if (in_array($breed, $dog_breeds)) {
                        echo '<img src="DC icons/dog_12353611.png" alt="Dog Icon" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">';
                    } elseif (in_array($breed, $cat_breeds)) {
                        echo '<img src="DC icons/tiger_414697.png" alt="Cat Icon" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">';
                    } else {
                        echo htmlspecialchars(substr($pet['name'], 0, 1));
                    }
                    ?>
                  <?php endif; ?>
                </div>
                <div class="pet-meta">
                  <h3><?php echo htmlspecialchars($pet['name']); ?> <small style="color:#5c6aa6">♂</small></h3>
                  <div class="meta-muted"><?php echo htmlspecialchars($pet['breed'] . ' | ' . $pet['age'] . ' | ' . $pet['weight'] . 'kg'); ?></div>
                  <div class="small" style="margin-top:8px;color:#a1a6c2">ID: <?php echo htmlspecialchars($pet['id']); ?></div>
                  <button onclick="window.location.href='symptoms-adding.php?pet_id=<?php echo $pet['id']; ?>'" style="background:#28a745;color:#fff;border:none;padding:4px 8px;border-radius:4px;font-size:12px;margin-top:4px;margin-right:4px;cursor:pointer;">Add Symptoms</button>
                  <form method="POST" style="display:inline;" id="delete-form-<?php echo $pet['id']; ?>">
                    <input type="hidden" name="delete_pet_id" value="<?php echo $pet['id']; ?>">
                    <button type="button" onclick="confirmDeletePet(<?php echo $pet['id']; ?>)" style="background:#ff4d4d;color:#fff;border:none;padding:4px 8px;border-radius:4px;font-size:12px;margin-top:4px;cursor:pointer;">Delete</button>
                  </form>
                </div>
              </div>
              <hr style="margin:10px 0;border:none;border-top:1px solid #f1f3fb">
            <?php endforeach; ?>
            <button class="btn-add" onclick="window.location.href='pet-adding.php'" style="width:100%;margin-top:10px;">Add More Pet</button>
          <?php endif; ?>

          <hr style="margin:14px 0;border:none;border-top:1px solid #f1f3fb">

          <div>
            <h4 style="margin:0 0 8px 0">Docs</h4>
            <?php if (empty($docs)): ?>
              <div class="small" style="color:#8b8fb1;padding:8px 0">No documents yet. Upload some to get started!</div>
            <?php else: ?>
              <?php foreach($docs as $d): ?>
                <div class="doc">
                  <div>
                    <div style="font-weight:600"><?php echo htmlspecialchars($d['name']); ?></div>
                    <div class="small"><?php echo htmlspecialchars($d['date']); ?></div>
                  </div>
                  <div style="font-size:18px;opacity:0.6">📄</div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- middle column -->
        <div class="card col-span-5">
          <h3 style="margin:0 0 12px 0">Symptoms</h3>
          <?php if (empty($symptoms_by_pet)): ?>
            <div style="display:flex;align-items:center;justify-content:center;color:#8b8fb1;font-size:14px;padding:20px;">
              No symptoms added yet. Add symptoms to your pets to get started!
            </div>
          <?php else: ?>
            <div class="tabs">
              <?php $first = true; ?>
              <?php foreach($symptoms_by_pet as $pet_id => $data): ?>
                <button class="tab-button <?php echo $first ? 'active' : ''; ?>" onclick="openTab(<?php echo $pet_id; ?>)"><?php echo htmlspecialchars($data['pet_name']); ?></button>
                <?php $first = false; ?>
              <?php endforeach; ?>
            </div>
            <?php $first = true; ?>
            <?php foreach($symptoms_by_pet as $pet_id => $data): ?>
              <div id="tab-<?php echo $pet_id; ?>" class="tab-content <?php echo $first ? 'active' : ''; ?>">
                <?php if (empty($data['symptoms'])): ?>
                  <div class="small" style="color:#8b8fb1;padding:8px 0">No symptoms for this pet.</div>
                <?php else: ?>
                  <?php foreach($data['symptoms'] as $symptom): ?>
                    <div class="med" style="padding:12px 0;border-bottom:1px solid #f1f3fb;">
                      <div style="display:flex;justify-content:space-between;align-items:center">
                        <div>
                          <div style="font-weight:600"><?php echo htmlspecialchars($symptom['symptom']); ?></div>
                          <div class="small"><?php echo htmlspecialchars(date('M d, Y', strtotime($symptom['date_added']))); ?></div>
                        </div>
                        <div style="display:flex;gap:4px;">
                          <form method="POST" style="display:inline;" id="delete-symptom-form-<?php echo $symptom['id']; ?>">
                            <input type="hidden" name="delete_symptom_id" value="<?php echo $symptom['id']; ?>">
                            <button type="button" onclick="confirmDeleteSymptom(<?php echo $symptom['id']; ?>)" style="background:#ff4d4d;color:#fff;border:none;padding:4px 8px;border-radius:4px;font-size:12px;cursor:pointer;">Delete</button>
                          </form>
                          <div style="opacity:0.6">⚠️</div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
              <?php $first = false; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          <hr style="margin:14px 0;border:none;border-top:1px solid #f1f3fb">

          <h3 style="margin:0 0 8px 0">Medications</h3>
          <?php if (empty($meds)): ?>
            <div class="small" style="color:#8b8fb1;padding:8px 0">No medications added. Add some to keep track!</div>
          <?php else: ?>
            <?php foreach($meds as $m): ?>
              <div class="med">
                <div style="display:flex;justify-content:space-between;align-items:center">
                  <div>
                    <div style="font-weight:600"><?php echo htmlspecialchars($m['name']); ?></div>
                    <div class="small"><?php echo htmlspecialchars($m['note']); ?></div>
                  </div>
                  <div style="opacity:0.6">⋮</div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- right column -->
        <div class="card col-span-3">
          <h3 style="margin:0 0 8px 0">Appointment</h3>
          <?php if (empty($appointment['date'])): ?>
            <div class="small" style="color:#8b8fb1">No upcoming appointment. Schedule one with your vet!</div>
          <?php else: ?>
            <div class="small">Date: <?php echo htmlspecialchars($appointment['date']); ?> | <?php echo htmlspecialchars($appointment['time']); ?></div>
            <div class="small">Vet: <?php echo htmlspecialchars($appointment['vet']); ?></div>
            <div class="small">Place: <?php echo htmlspecialchars($appointment['place']); ?></div>
          <?php endif; ?>

          <hr style="margin:12px 0;border:none;border-top:1px solid #f1f3fb">

          <h4 style="margin:0 0 8px 0">Coming events</h4>
          <?php if (empty($events)): ?>
            <div class="small" style="color:#8b8fb1;padding:8px 0">No events scheduled. Add reminders for vaccinations or grooming!</div>
          <?php else: ?>
            <?php foreach($events as $e): ?>
              <div class="event">
                <div style="font-weight:600"><?php echo htmlspecialchars($e['title']); ?></div>
                <div class="small"><?php echo htmlspecialchars($e['date']); ?></div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

        </div>
      </section>

    </main>
  </div>

  <!-- Artemis Chat Widget -->
  <div id="artemis-bubble">🐾</div>
  <div id="artemis-chatbox" class="hidden">
    <div class="chat-header">Artemis 🐾</div>
    <div class="chat-body"></div>
    <div class="chat-input">
      <input type="text" placeholder="Type your message...">
      <button>Send</button>
    </div>
  </div>

  <script src="chat-widget.js"></script>
  <script>
    // Button functionalities
    function navigate(section) {
      switch(section) {
        case 'Pets':
          // Already on pets dashboard
          break;
        case 'Vets':
          window.location.href = 'vets.php';
          break;
        case 'Profile':
          alert('Profile section - Functionality to be implemented.');
          break;
        case 'Settings':
          alert('Settings section - Functionality to be implemented.');
          break;
        case 'Support':
          alert('Support section - Functionality to be implemented.');
          break;
      }
    }

    function goBack() {
      window.location.href = 'role-select.php';
    }

    function confirmDeletePet(petId) {
      if (confirm('Are you sure you want to delete this pet? This action cannot be undone.')) {
        document.getElementById('delete-form-' + petId).submit();
      }
    }

    function confirmDeleteSymptom(symptomId) {
      if (confirm('Are you sure you want to delete this symptom? This action cannot be undone.')) {
        document.getElementById('delete-symptom-form-' + symptomId).submit();
      }
    }

    function openTab(petId) {
      // Hide all tab contents
      const tabContents = document.querySelectorAll('.tab-content');
      tabContents.forEach(content => content.classList.remove('active'));

      // Remove active class from all tab buttons
      const tabButtons = document.querySelectorAll('.tab-button');
      tabButtons.forEach(button => button.classList.remove('active'));

      // Show the selected tab content
      document.getElementById('tab-' + petId).classList.add('active');

      // Add active class to the clicked button
      event.target.classList.add('active');
    }
  </script>
</body>
</html>