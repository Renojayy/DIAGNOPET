<?php
session_start();
include 'db_connect.php';

// Reset old session if somehow left behind
if (isset($_SESSION['petowner_logged_in'])) {
    session_unset();
    session_destroy();
    session_start(); // start fresh session
}

// Redirect logged-in users (should be false now)
if (isset($_SESSION['petowner_logged_in']) && $_SESSION['petowner_logged_in'] === true) {
    header("Location: petowner_dashboard.php");
    exit();
}

$user_name = 'Guest';
$logged_in = false;
$pets = [];
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>DIAGNOPET - Pet Dashboard (Guest)</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
/* ---------------- Styles copied from your previous guest dashboard ---------------- */
:root{
  --bg:#f3f6ff; --card:#ffffff; --muted:#8b8fb1; --accent:#5c4fff; --glass: rgba(255,255,255,0.7);
  --shadow: 0 6px 18px rgba(36,41,90,0.08);
}
*{box-sizing:border-box}
body{font-family:Inter,system-ui,Arial; margin:0; background: linear-gradient(180deg,#eef2ff 0%, #f7f9ff 100%); color:#222}
.wrap{max-width:1200px; margin:36px auto; padding:26px; background:linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.95)); border-radius:14px; box-shadow: 0 8px 30px rgba(39,45,90,0.06); display:grid; grid-template-columns:84px 1fr; gap:18px}
.sidebar{padding:18px; display:flex; flex-direction:column; gap:18px; align-items:center}
.logo{width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#7b6bff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;box-shadow:var(--shadow)}
.nav{display:flex;flex-direction:column;gap:16px;margin-top:6px}
.nav button{width:48px;height:48px;border-radius:12px;border:none;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer}
.nav button.active{background:rgba(92,79,255,0.08)}
.logout{margin-top:auto;opacity:0.7}
.main{padding:8px}
.header{display:flex;align-items:center;justify-content:space-between;padding:8px 10px}
.avatar{display:flex;gap:10px;align-items:center}
.avatar img{width:36px;height:36px;border-radius:50%}
.btn-add{background:var(--accent); color:#fff;padding:10px 14px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 8px 20px rgba(92,79,255,0.18)}
.grid{display:grid;grid-template-columns:repeat(12,1fr);gap:16px}
.card{background:var(--card); border-radius:12px; padding:18px; box-shadow:var(--shadow)}
.card.col-span-4{grid-column:span 4}
.card.col-span-5{grid-column:span 5}
.card.col-span-3{grid-column:span 3}
.small{font-size:13px;color:var(--muted)}
.pet-card{display:flex;align-items:center;gap:12px}
.pet-avatar{width:72px;height:72px;border-radius:14px;background:#f1f3ff;display:flex;align-items:center;justify-content:center;font-weight:600}
.pet-meta{line-height:1}
.pet-meta h3{margin:0}
.meta-muted{font-size:13px;color:#6b6f88}
.doc{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3fb}
.doc:last-child{border-bottom:none}
.med{padding:8px 0;border-bottom:1px dashed #f1f3fb}
.med:last-child{border-bottom:none}
.event{padding:10px;border-radius:10px;background:linear-gradient(180deg,#fff,#fcfcff);margin-bottom:10px}
.tabs{display:flex;border-bottom:1px solid #f1f3fb;margin-bottom:16px}
.tab-button{padding:8px 16px;border:none;background:none;color:#8b8fb1;cursor:pointer;border-bottom:2px solid transparent}
.tab-button.active{color:#5c4fff;border-bottom-color:#5c4fff;font-weight:600}
.tab-content{display:none}
.tab-content.active{display:block}
/* Chat Widget Styles */
#artemis-bubble {position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: var(--shadow); z-index: 1000; font-size: 24px;}
#artemis-chatbox {position: fixed; bottom: 90px; right: 20px; width: 300px; height: 400px; background: var(--card); border-radius: 12px; box-shadow: var(--shadow); display: flex; flex-direction: column; z-index: 1000;}
#artemis-chatbox.hidden {display: none;}
.chat-header {padding: 10px; background: var(--accent); color: white; border-radius: 12px 12px 0 0; font-weight: 600;}
.chat-body {flex: 1; padding: 10px; overflow-y: auto;}
.chat-message {margin-bottom: 10px; padding: 8px 12px; border-radius: 8px; max-width: 80%;}
.chat-message.user {background: var(--accent); color: white; align-self: flex-end; margin-left: auto;}
.chat-message.bot {background: #f1f3fb; color: #222;}
.chat-input {display: flex; padding: 10px; border-top: 1px solid #f1f3fb;}
.chat-input input {flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-right: 8px;}
.chat-input button {padding: 8px 12px; background: var(--accent); color: white; border: none; border-radius: 4px; cursor: pointer;}
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
        <button class="btn-add" onclick="showLoginModal()">Add Pet</button>
        <div class="avatar"><div style="font-size:14px"><?php echo htmlspecialchars($user_name); ?></div></div>
      </div>
    </div>

    <section class="grid" style="margin-top:16px">
      <div class="card col-span-4">
        <h4 style="margin:0 0 12px 0">Your Pets</h4>
        <div class="pet-card">
          <div class="pet-avatar">👤</div>
          <div class="pet-meta">
            <h3 style="color:#8b8fb1">Guest Mode</h3>
            <div class="meta-muted">Log in to manage your pets and access full features.</div>
          </div>
        </div>
      </div>

      <div class="card col-span-5">
        <h3 style="margin:0 0 12px 0">Symptoms</h3>
        <div style="display:flex;align-items:center;justify-content:center;color:#8b8fb1;font-size:14px;padding:20px;">
          Log in to track your pets' symptoms and health history.
        </div>
      </div>

      <div class="card col-span-3">
        <h4 style="margin:0 0 8px 0">Coming events</h4>
        <div class="small" style="color:#8b8fb1;padding:8px 0">Log in to view upcoming appointments and events.</div>
      </div>
    </section>
  </main>
</div>

<!-- Login Required Modal -->
<div id="loginModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:20px 30px;border-radius:12px;max-width:400px;width:90%;text-align:center;box-shadow:0 10px 25px rgba(0,0,0,0.2);">
    <h3 style="margin-bottom:12px;color:#5c4fff;">Login Required</h3>
    <p style="margin-bottom:20px;color:#555;">You need to log in to access this feature.</p>
    <button onclick="window.location.href='pet-login.php'" style="padding:8px 16px;background:#5c4fff;color:#fff;border:none;border-radius:8px;cursor:pointer;">Log In</button>
    <button onclick="closeLoginModal()" style="padding:8px 16px;margin-left:8px;background:#ccc;color:#222;border:none;border-radius:8px;cursor:pointer;">Cancel</button>
  </div>
</div>

<script>
function showLoginModal() { document.getElementById('loginModal').style.display='flex'; }
function closeLoginModal() { document.getElementById('loginModal').style.display='none'; }
function navigate(section) { showLoginModal(); } // all guest features trigger modal
function goBack() { window.location.href='role-select.php'; }
</script>
</body>
</html>
