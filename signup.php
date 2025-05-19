<?php
session_start();
$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex">
  <!-- Left section (form) -->
   <div class="w-1/2 flex flex-col justify-center items-center bg-white">
    <!-- Logo -->
   <img src="img/logo.png" alt="logo" class="w-48 h-48">
   <div class="w-full max-w-sm font-serif">
    <!-- Title -->
     <h2 class="text-2xl font-bold mb-4">Sign up</h2>

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
        </div><br>
        <button type="submit" name="signup" class="w-1/2 py-2 mx-auto block bg-black text-white hover:bg-gray-800">Create Account</button>
      </form>
      <br><br>
      <!-- Sign up link -->
      <p class="mt-4 text-sm">Already have an account? <a href="login.php" class="underline text-black">Login</a></p>
      <br>
    </div>
  </div>

  <!-- Right section (advertisement) -->
   <img src="img/signup-ad.jpg" alt="signup-advertisement" class="fixed top-0 right-0 w-1/2 h-screen object-cover">
</body>
</html>