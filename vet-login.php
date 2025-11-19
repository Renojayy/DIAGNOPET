<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vet Login - Diagnopet</title>
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
<body class="bg-gradient-to-b from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4 overflow-hidden">
  <!-- Floating decorative elements -->
  <div class="absolute w-32 h-32 rounded-full bg-primary/10 top-1/4 left-1/5 animate-float"></div>
  <div class="absolute w-24 h-24 rounded-full bg-secondary/10 bottom-1/4 right-1/5 animate-float-delay"></div>
  <div class="absolute w-20 h-20 rounded-full bg-accent/10 top-1/3 right-1/4 animate-float-delay-2"></div>
  <div class="absolute w-16 h-16 rounded-full bg-primary/10 bottom-1/3 left-1/4 animate-float"></div>

  <!-- Main card -->
  <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative z-10 backdrop-blur-sm bg-white/90">
    <div class="text-center mb-8">
      <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <i data-feather="log-in" class="text-primary w-8 h-8"></i>
      </div>
      <h2 class="text-3xl font-bold text-gray-800 mb-2">Vet Login</h2>
      <p class="text-gray-600">Welcome back to Diagnopet</p>
    </div>

    <form id="vetForm" action="vet-login.php" method="POST" class="space-y-4">
      <div class="relative">
        <i data-feather="mail" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="email" name="email" id="email" placeholder="Email Address" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <div class="relative">
        <i data-feather="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="password" name="password" id="password" placeholder="Password" required
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
      </div>

      <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
        <i data-feather="log-in" class="w-5 h-5"></i>
        Login
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="vetlanding.php" class="text-gray-600 hover:text-primary transition flex items-center justify-center gap-1">
        <i data-feather="arrow-left" class="w-4 h-4"></i>
        Back to previous page
      </a>
      <p>
        Don't have an Account?
        <a href="vet-register.php" style="color: blue;">Register Here</a>
      </p>
    </div>
  </div>

  <script>
    feather.replace();
  </script>
</body>
</html>
