<?php // Veterinary Login Landing Page ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Veterinary Login | DIAGNOPET</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', Georgia;
      background: #fff;
      color: #003366;
      overflow-x: hidden;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }

    .left {
      width: 50%;
    }

    .left h1 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .left p {
      color: #336699;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .login-btn {
      padding: 12px 30px;
      background: #fff;
      color: #007bff;
      border-radius: 30px;
      font-weight: 600;
      border: 2px solid #007bff;
      cursor: pointer;
      font-size: 16px;
    }

    .login-btn a {
      text-decoration: none;
      color: #007bff;
    }

    .search-box {
      margin-top: 20px;
      display: flex;
      background: #f3f3f3;
      border-radius: 40px;
      padding: 10px;
      align-items: center;
      width: 90%;
    }

    .search-box input {
      flex: 1;
      border: none;
      background: transparent;
      font-size: 16px;
      outline: none;
      padding: 5px;
    }

    .search-box button {
      background: #007bff;
      border: none;
      color: #fff;
      padding: 10px 20px;
      border-radius: 30px;
      cursor: pointer;
    }

    .right {
      width: 45%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .image-holder {
      width: 90%;
      height: 450px;
      background: #eee;
      border-radius: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #888;
      font-size: 18px;
      font-weight: 600;
      border: 2px dashed #ccc;
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
      50% { transform: translateY(-20px) scale(1.1); }
      100% { transform: translateY(0) scale(1); }
    }

    /* Responsive Design */
    @media (max-width: 900px) {
      .container {
        flex-direction: column;
        text-align: center;
        padding: 30px 20px;
      }

      .left, .right {
        width: 100%;
      }

      .left h1 {
        font-size: 32px;
      }

      .search-box {
        width: 100%;
        justify-content: center;
      }

      .image-holder {
        width: 100%;
        height: 300px;
        margin-top: 20px;
      }
    }

    @media (max-width: 500px) {
      header {
        flex-direction: column !important;
        text-align: center;
      }

      nav a {
        display: inline-block;
        margin: 8px 10px !important;
      }

      .left h1 {
        font-size: 26px;
      }

      .left p {
        font-size: 14px;
      }

      .login-btn {
        width: 100%;
      }

      .search-box input {
        font-size: 14px;
      }
    }

  </style>
</head>
<body>
  <div class="bubble" style="width:120px;height:120px;top:20%;left:15%;animation-delay:0s;"></div>
  <div class="bubble" style="width:80px;height:80px;top:60%;left:70%;animation-delay:2s;"></div>
  <div class="bubble" style="width:100px;height:100px;top:40%;left:40%;animation-delay:4s;"></div>
  <div class="bubble" style="width:60px;height:60px;top:80%;left:25%;animation-delay:1s;"></div>

  <header style="padding: 20px 40px; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="margin: 0; font-weight: 700;">DIAGNOPET</h2>
    <nav>
      <a href="index.php" style="margin-right: 20px; color: #333; text-decoration: none;">Home</a>
      <a href="vet-login.php" style="padding: 10px 20px; border: 2px solid #007bff; border-radius: 30px; text-decoration: none; color:#007bff; font-weight:600;">Login</a>
    </nav>
  </header>

  <div class="container">
    <div class="left">
      <h1>Caring for Pets Starts With You</h1>
      <p>Log in to manage veterinary appointments, pet records, and provide the best care for your furry patients.</p>
      <button class="login-btn"><a href="vet-register.php">Sign up </a> </button> 

      <div class="search-box">
       
      </div>
    </div>

    <div class="right">
      <div class="image-holder">
        Insert Pet Image Here
      </div>
    </div>
  </div>

</body>
</html>
