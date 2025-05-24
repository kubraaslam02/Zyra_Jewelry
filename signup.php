<?php
session_start();
$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col md:flex-row">
  <!-- Left section (form) -->
  <div class="w-full md:w-1/2 flex items-center justify-center bg-white min-h-screen md:min-h-0 relative z-10">
    <div class="w-full max-w-sm font-serif px-6">
      <!-- Logo -->
      <img src="img/logo.png" alt="logo" class="w-32 h-32 mx-auto mb-4">
      <!-- Title -->
      <h2 class="text-2xl font-bold mb-2 text-center md:text-left">Sign up</h2>
      <br>
      <!-- Error Message -->
      <?php if ($error): ?>
        <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <!-- Signup Form -->
      <form action="route.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Username:</label>
          <input type="username" name="username" required class="w-full p-2 rounded-xl bg-gray-200 focus:outline-none" />
        </div>

        <div>
          <label class="block text-sm font-medium">Email:</label>
          <input type="email" name="email" required class="w-full p-2 rounded-xl bg-gray-200 focus:outline-none" />
        </div>

        <div>
          <label class="block text-sm font-medium">Password:</label>
          <input type="password" name="password" required class="w-full p-2 rounded-xl bg-gray-200 focus:outline-none" />
        </div>
        <br>

        <button type="submit" name="signup" class="w-1/2 py-2 mx-auto block bg-black text-white hover:bg-gray-800">Create Account</button>
      </form>
      <br>
      <!-- Sign up link -->
      <p class="mt-4 text-sm">Already have an account? <a href="login.php" class="underline text-black">Login</a></p>
      <br>
    </div>
  </div>

  <!-- Right section (advertisement) -->
  <img src="img/signup-ad.jpg" alt="signup-advertisement" class="hidden md:block w-full h-screen object-cover md:w-1/2 md:h-auto md:fixed md:top-0 md:right-0">
</body>
</html>