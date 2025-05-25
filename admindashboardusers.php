<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Redirect normal users to index.php page; only admins allowed here
if ($_SESSION['user']['usertype'] === 'user') {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['user_error'])) {
    echo '<div class="error-message" style="color:red; font-weight:bold;">' . htmlspecialchars($_SESSION['user_error']) . '</div>';
    unset($_SESSION['user_error']);
}

require_once "functions.php";

// Fetch all users from the database
$users = getAllUsers();

// Retrieve flash messages from session (if any)
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;

// Clear flash messages so they don't show repeatedly
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">

<!-- Navbar -->
<nav class="bg-white shadow px-4 py-2">
    <div class="flex items-center justify-between md:justify-start">
        <!-- Logo -->
        <a href="admindashboardorders.php" class="mr-auto">
            <img src="img/logo.png" alt="logo" class="w-10 h-10 md:w-32 md:h-32">
        </a>

        <!-- Hamburger menu button for mobile -->
        <button id="menu-button" class="md:hidden">
            <img src="img/menu.png" alt="menu" class="w-6 h-6">
        </button>

        <!-- Desktop menu -->
        <div class="hidden md:flex md:flex-1 md:items-center md:justify-between w-full">
            <div class="flex justify-center space-x-6 mx-auto">
                <!-- Navigation links highlighting the active page -->
                <a href="admindashboardorders.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admindashboardorders.php' ? 'font-bold text-black underline' : ''; ?>">View Orders</a>
                <a href="admindashboardproducts.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admindashboardproducts.php' ? 'font-bold text-black underline' : ''; ?>">Manage Products</a>
                <a href="admindashboardusers.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admindashboardusers.php' ? 'font-bold text-black underline' : ''; ?>">Manage Users</a>
            </div>

            <!-- Logout button -->
            <div class="flex items-center space-x-8 px-6">
                <a href="logout.php" class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800">Logout</a>
            </div>
        </div>
    </div>

    <!-- Mobile menu highlighting the active page -->
    <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-4">
        <a href="admindashboardorders.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admindashboardorders.php' ? 'font-bold text-black underline' : ''; ?>">View Orders</a>
        <a href="admindashboardproducts.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admindashboardproducts.php' ? 'font-bold text-black underline' : ''; ?>">Manage Products</a>
        <a href="admindashboardusers.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) === 'admindashboardusers.php' ? 'font-bold text-black underline' : ''; ?>">Manage Users</a>
        <a href="logout.php" class="block px-3 py-2 bg-black text-white rounded text-center hover:bg-gray-800">Logout</a>
    </div>
</nav>

<!-- Main section -->
<section class="my-8 px-4">
    <div class="text-center mb-4">
        <h2 class="text-3xl font-bold pb-6">Registered Users</h2>
    </div>

    <div class="max-w-7xl mx-auto">

        <!-- Show error message if exists -->
        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?php echo htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Show success message if exists -->
        <?php if ($success): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded"><?php echo htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="overflow-x-auto bg-white shadow rounded mb-8">
            <table class="min-w-full border border-black">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-6 py-3">User ID</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Password</th>
                        <th class="px-6 py-3">Membership</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-black text-center">
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo $user['id']; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']) ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['password']) ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['membership']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Add User Form -->
            <section class="bg-white p-6 border border-black rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Add User</h2>
                <form action="route.php" method="POST" class="space-y-2">
                    <label>Username:</label>
                    <input type="text" name="username" required class="w-full border p-2 rounded">

                    <label>Email:</label>
                    <input type="email" name="email" required class="w-full border p-2 rounded">

                    <label>Password:</label>
                    <input type="password" name="password" required class="w-full border p-2 rounded">
                    
                    <label>Membership:</label>
                    <select name="membership" class="w-full border p-2 rounded">
                        <option value=""></option>
                        <option>Stadard Membership</option>
                        <option>Premium Membership</option>
                    </select>

                    <div class="flex justify-center mt-4">
                        <button type="submit" name="add_user" class="bg-black hover:bg-gray-700 text-white px-4 py-2 rounded">Add User</button>
                    </div>
                </form>
            </section>

            <!-- Update User Form -->
            <section class="bg-white p-6 border border-black rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Update User</h2>
                <form action="route.php" method="POST" class="space-y-4">
                    <label>User ID:</label>
                    <input type="number" name="id" required class="w-full border p-2 rounded">

                    <label>Username:</label>
                    <input type="text" name="name" class="w-full border p-2 rounded">

                    <label>Email:</label>
                    <input type="email" name="email" class="w-full border p-2 rounded">

                    <label>Password:</label>
                    <input type="password" name="password" class="w-full border p-2 rounded">

                    <label>Membership:</label>
                    <select name="membership" class="w-full border p-2 rounded">
                        <option value=""></option>
                        <option>Stadard Membership</option>
                        <option>Premium Membership</option>
                    </select>

                    <div class="flex justify-center mt-4">
                        <button type="submit" name="update_user" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Update User</button>
                    </div>
                </form>
            </section>
        </div>

        <!-- Delete User Form -->
        <section class="bg-white border border-black p-6 rounded shadow mt-8 max-w-md">
            <h2 class="text-xl font-semibold mb-4">Delete User</h2>
            <form action="route.php" method="POST" class="space-y-4">
                <label>User ID:</label>
                <input type="text" name="delete_id" required class="w-full border p-2 rounded">

                <button type="submit" name="delete_user" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Delete User</button>
            </form>
        </section>
    </div>
</section>

<!-- Footer -->
<footer class="border-t-4 py-8 px-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
    <div>
        <h3 class="font-semibold mb-2">Socials</h3>
        <div class="flex gap-4">
            <img src="img/whatsapp.png" alt="whatsapp" class="size-6">
            <img src="img/instagram.png" alt="instagram" class="size-6">
            <img src="img/tik-tok.png" alt="tiktok" class="size-6">
        </div>
    </div>
    <div>
        <h3 class="font-semibold mb-2">Quick Links</h3>
        <a href="admindashboardorders.php" class="block underline">View Orders</a>
        <a href="admindashboardproducts.php" class="block underline">Manage Products</a>
        <a href="admindashboardusers.php" class="block underline">Manage Users</a>
    </div>
</footer>
<script src="main.js" defer></script>
</body>
</html>