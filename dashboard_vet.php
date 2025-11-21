<?php
// --- Sample dynamic data (replace with DB queries) ---
$metrics = [
    'upcoming' => 120,
    'finished' => 72,
    'finance' => '3,450',
];

$recentSales = [
    ['label' => 'Oct', 'value' => 40],
    ['label' => 'Nov', 'value' => 65],
    ['label' => 'Dec', 'value' => 80],
];

$quickActions = [
    ['title' => 'Create Appointment', 'icon' => 'calendar-plus', 'link' => '#'],
    ['title' => 'Create Counter Sale', 'icon' => 'shopping-cart', 'link' => '#'],
    ['title' => 'Create New Patient', 'icon' => 'user-plus', 'link' => '#'],
    ['title' => 'Generate Monthly Report', 'icon' => 'file-alt', 'link' => '#'],
];

$timeline = [
    ['time' => '09:00', 'title' => 'Initial Examination', 'meta' => 'Room 1 - Dr. Cruz'],
    ['time' => '10:30', 'title' => 'Digital X-Ray', 'meta' => 'Imaging - Dr. Reyes'],
    ['time' => '13:00', 'title' => 'Vaccination', 'meta' => 'Clinic - Nurse Ana'],
    ['time' => '15:30', 'title' => 'Consultation', 'meta' => 'Room 2 - Dr. Lim'],
];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Diagnopet Dashboard</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{ --accent:#54B4A4; --muted:#6c757d }
    body{ background:#f6fbfb; }
    .sidebar{ background:#fff; min-height:100vh; border-right:1px solid #eef2f4 }
    .sidebar .nav-link{ color:#556; }
    .card-ghost{ background:linear-gradient(90deg, #ffffff 0%, rgba(84,180,164,0.06) 100%); border: none; }
    .metric { padding: 18px; border-radius: 12px; }
    .metric .value{ font-weight:700; font-size:1.7rem }
    .quick-action{ border-radius:12px; padding:18px; min-height:110px }
    .timeline .item{ background:#fff; border-radius:10px; padding:12px; margin-bottom:12px; box-shadow:0 1px 4px rgba(0,0,0,0.04) }
    .searchbar{ background:#fff; border-radius:12px; padding:10px 14px; box-shadow: 0 1px 6px rgba(18,38,63,0.04) }
    .avatar{ width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,#6dd3cb,#3fa08c); display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700 }
    @media (max-width:991px){ .sidebar{ display:none } }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- SIDEBAR -->
    <aside class="col-12 col-lg-2 sidebar p-3 d-flex flex-column gap-3">
      <div class="d-flex align-items-center gap-2">
        <div class="avatar">DT</div>
        <div>
          <div class="fw-bold">Diagnopet</div>
          <div class="text-muted" style="font-size:.85rem">Veterinary Support</div>
        </div>
      </div>
      <nav class="nav flex-column mt-3">
        <a class="nav-link active" href="#"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a class="nav-link" href="#appointments"><i class="fas fa-stethoscope me-2"></i> Appointments</a>
        <a class="nav-link" href="#patients"><i class="fas fa-paw me-2"></i> Patients</a>
        <a class="nav-link" href="#"><i class="fas fa-file-invoice-dollar me-2"></i> Billing</a>
        <a class="nav-link" href="#"><i class="fas fa-cog me-2"></i> Settings</a>
      </nav>
      <div class="mt-auto text-muted small">&copy; Diagnopet</div>
    </aside>

    <!-- MAIN -->
    <main class="col-12 col-lg-8 p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <div class="h5 mb-0">Hello, <strong>Sylwia</strong>!</div>
          <small class="text-muted">Welcome back — here’s a summary of today</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div class="searchbar d-flex align-items-center gap-2">
            <i class="fas fa-search text-muted"></i>
            <input class="form-control border-0 p-0" style="min-width:220px;" placeholder="Search for patients or medicine">
          </div>
          <button class="btn btn-outline-secondary"><i class="fas fa-bell"></i></button>
        </div>
      </div>

      <!-- METRICS -->
      <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
          <div class="card metric card-ghost p-3">
            <div class="d-flex justify-content-between align-items-start">
              <small class="text-muted">Upcoming Appt.</small>
              <i class="fas fa-calendar-check fa-lg text-muted"></i>
            </div>
            <div class="value mt-2"><?php echo htmlspecialchars($metrics['upcoming']); ?></div>
            <small class="text-success">4 not confirmed</small>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card metric p-3">
            <div class="d-flex justify-content-between align-items-start">
              <small class="text-muted">Finished Appt.</small>
              <i class="fas fa-check-circle fa-lg text-muted"></i>
            </div>
            <div class="value mt-2"><?php echo htmlspecialchars($metrics['finished']); ?></div>
            <small class="text-success">3.8% vs last month</small>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card metric p-3">
            <div class="d-flex justify-content-between align-items-start">
              <small class="text-muted">Finance</small>
              <i class="fas fa-wallet fa-lg text-muted"></i>
            </div>
            <div class="value mt-2">₱ <?php echo htmlspecialchars($metrics['finance']); ?></div>
            <small class="text-success">6.8% vs last month</small>
          </div>
        </div>
      </div>

      <!-- MAIN CONTENT CARDS -->
      <div class="row g-3">
        <div class="col-12 col-md-7">
          <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="mb-0">Recent Goods Sales</h6>
              <small class="text-muted">You have sold 48 products this month</small>
            </div>
            <div style="height:180px; display:flex; align-items:center; justify-content:center; color:var(--muted);">[ Chart placeholder ]</div>
            <div class="text-end mt-2"><a href="#">View details</a></div>
          </div>
        </div>
        <div class="col-12 col-md-5">
          <div class="row g-2">
            <?php foreach($quickActions as $action): ?>
            <div class="col-6">
              <a href="<?php echo $action['link']; ?>" class="d-block quick-action card p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-start gap-2">
                  <div class="bg-light rounded-3 p-2"><i class="fas fa-<?php echo $action['icon']; ?> fa-lg"></i></div>
                  <div>
                    <div class="fw-bold" style="font-size:.95rem"><?php echo htmlspecialchars($action['title']); ?></div>
                    <small class="text-muted">Quick action</small>
                  </div>
                </div>
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- ADDITIONAL ROW -->
      <div class="row g-3 mt-3">
        <div class="col-12 col-md-6">
          <div class="card p-3">
            <h6>Tasks</h6>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Review lab results <span class="badge bg-secondary float-end">high</span></li>
              <li class="list-group-item">Approve inventory order <span class="badge bg-secondary float-end">low</span></li>
              <li class="list-group-item">Follow up: Bella (cat)</li>
            </ul>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="card p-3">
            <h6>Chat Messages</h6>
            <div class="small text-muted">No new messages</div>
          </div>
        </div>
      </div>

      <!-- APPOINTMENTS SECTION -->
      <div id="appointments" class="row g-3 mt-3">
        <div class="col-12">
          <div class="card p-3">
            <h6>Appointments and Consultations</h6>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Pet Owner</th>
                  <th>Vet ID</th>
                  <th>Date</th>
                  <th>Symptoms</th>
                  <th>Remarks</th>
                  <th>Threat Level</th>
                </tr>
              </thead>
              <tbody>
                <?php
                include 'db_connect.php';
                $sql = "SELECT * FROM consultations";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["consultation_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Pet Owner"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Veterinary ID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Consultations Date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Symptoms Discussed"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Remarks"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Level of Threats"]) . "</td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='7'>No consultations found</td></tr>";
                }
                $conn->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PATIENTS SECTION -->
      <div id="patients" class="row g-3 mt-3">
        <div class="col-12">
          <div class="card p-3">
            <h6>Patients</h6>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Pet Name</th>
                  <th>Species</th>
                  <th>Breed</th>
                  <th>Age</th>
                  <th>Owner</th>
                  <th>Gender</th>
                </tr>
              </thead>
              <tbody>
                <?php
                include 'db_connect.php';
                $sql = "SELECT * FROM pets";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["pet_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Pet Name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Pet Type"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Pet Breed"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Pet Age"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["user_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Pet Gender"]) . "</td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='7'>No patients found</td></tr>";
                }
                $conn->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </main>

    <!-- RIGHT TIMELINE -->
    <aside class="col-12 col-lg-2 p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <small class="text-muted">Today, <?php echo date('d M.'); ?></small>
        </div>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
      </div>

      <div class="timeline">
        <?php foreach($timeline as $item): ?>
          <div class="item">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <div class="fw-bold small"><?php echo htmlspecialchars($item['title']); ?></div>
              <div class="text-muted small"><?php echo htmlspecialchars($item['time']); ?></div>
            </div>
            <div class="text-muted small"><?php echo htmlspecialchars($item['meta']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>

    </aside>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>