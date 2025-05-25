<?php
session_start();

// Retrieve any error message stored in the session
$error = $_SESSION['error'] ?? "";

// Clear the error from session after retrieving it
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col md:flex-row">
  <!-- Left section -->
  <div class="w-full md:w-1/2 flex items-center justify-center bg-white min-h-screen md:min-h-0 relative z-10">
    <div class="w-full max-w-sm font-serif px-6">
      <!-- Logo image -->
      <img src="img/logo.png" alt="logo" class="w-32 h-32 mx-auto mb-4" />
      <h2 class="text-2xl font-bold mb-2 text-center md:text-left">Login</h2>
      <p class="mb-6 text-gray-600 text-center md:text-left">Welcome back! Please login to your account</p>

      <!-- Display error message if any -->
      <?php if ($error): ?>
        <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <!-- Login form -->
      <form action="route.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Email:</label>
          <input type="email" name="email" required class="w-full p-2 rounded-xl bg-gray-200 focus:outline-none"/>
        </div>

        <div>
          <label class="block text-sm font-medium">Password:</label>
          <input type="password" name="password" required class="w-full p-2 rounded-xl bg-gray-200 focus:outline-none"/>
        </div>

        <button type="submit" name="login" class="w-1/2 py-2 mx-auto block bg-black text-white hover:bg-gray-800">Login</button>
      </form>

      <!-- Link to sign up page -->
      <p class="mt-4 text-sm">New User? 
        <a href="signup.php" class="underline text-black">Sign up</a>
      </p>
    </div>
  </div>

  <!-- Right section -->
  <img src="img/login-ad.jpg" alt="login-advertisement" class="hidden md:block w-full h-screen object-cover md:w-1/2 md:h-auto md:fixed md:top-0 md:right-0"/>
</body>
</html>