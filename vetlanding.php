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
      font-family: 'Poppins', sans-serif;
      background: #ffffff;
      color: #333;
      line-height: 1.6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 60px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .left {
      width: 50%;
    }

    .left h1 {
      font-size: 48px;
      font-weight: 700;
      margin-bottom: 20px;
      color: #007bff;
    }

    .left p {
      color: #666;
      margin-bottom: 40px;
      font-size: 18px;
    }

    .login-btn {
      padding: 15px 40px;
      background: #007bff;
      color: #fff;
      border-radius: 50px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      font-size: 18px;
      text-decoration: none;
      display: inline-block;
    }

    .right {
      width: 45%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .image-holder {
      width: 100%;
      height: 500px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .image-holder img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Responsive Design */
    @media (max-width: 900px) {
      .container {
        flex-direction: column;
        text-align: center;
        padding: 40px 20px;
      }

      .left, .right {
        width: 100%;
      }

      .left h1 {
        font-size: 36px;
      }

      .left p {
        font-size: 16px;
      }

      .image-holder {
        width: 100%;
        height: 350px;
        margin-top: 40px;
      }
    }

    @media (max-width: 500px) {
      .left h1 {
        font-size: 28px;
      }

      .left p {
        font-size: 14px;
      }

      .login-btn {
        width: 100%;
        padding: 12px 20px;
      }
    }

  </style>
</head>
<body>


  <header style="padding: 20px 40px; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="margin: 0; font-weight: 700; color: #007bff;">DIAGNOPET</h2>
    <nav>
      <a href="index.php" style="margin-right: 20px; color: #333; text-decoration: none;">Home</a>
      <a href="vet-login.php" style="padding: 10px 20px; border: 2px solid #007bff; border-radius: 30px; text-decoration: none; color:#007bff; font-weight:600;">Login</a>
    </nav>
  </header>

  <div class="container">
    <div class="left">
      <h1>Caring for Pets Starts With You</h1>
      <p>Log in to manage veterinary appointments, pet records, and provide the best care for your furry patients.</p>
      <a href="vet-register.php" class="login-btn">Sign up</a>
    </div>

    <div class="right">
      <div class="image-holder">
        <img src="DC icons/veterinarian.jpg" alt="Veterinarian caring for pets">
      </div>
    </div>
  </div>

</body>
</html>
