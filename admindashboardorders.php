<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Redirect regular users to index.php (only admins allowed here)
if ($_SESSION['user']['usertype'] === 'user') {
    header("Location: index.php");
    exit();
}

require_once "functions.php";

// Fetch all orders from the database
$orders = getAllOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View All Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">
    <!-- Navbar -->
    <nav class="bg-white shadow px-4 py-2">
        <div class="flex items-center justify-between md:justify-start">
            <!-- Logo -->
            <a href="admindashboardorders.php" class="mr-auto">
                <img src="img/logo.png" alt="logo" class="w-10 h-10 md:w-32 md:h-32" />
            </a>

            <!-- Hamburger button for mobile menu -->
            <button id="menu-button" class="md:hidden">
                <img src="img/menu.png" alt="menu" class="w-6 h-6" />
            </button>

            <!-- Desktop menu -->
            <div class="hidden md:flex md:flex-1 md:items-center md:justify-between w-full">
                <!-- Navigation links highlighting the active page-->
                <div class="flex justify-center space-x-6 mx-auto">
                    <a href="admindashboardorders.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardorders.php' ? 'font-bold text-black underline' : ''; ?>">View Orders</a>
                    <a href="admindashboardproducts.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardproducts.php' ? 'font-bold text-black underline' : ''; ?>">Manage Products</a>
                    <a href="admindashboardusers.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardusers.php' ? 'font-bold text-black underline' : ''; ?>">Manage Users</a>
                </div>

                <!-- Logout button -->
                <div class="flex items-center space-x-8 px-6">
                    <a href="logout.php" class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800">Logout</a>
                </div>
            </div>
        </div>

        <!-- Mobile menu highlighting the active page -->
        <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-4">
            <a href="admindashboardorders.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardorders.php' ? 'font-bold text-black underline' : ''; ?>">View Orders</a>
            <a href="admindashboardproducts.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardproducts.php' ? 'font-bold text-black underline' : ''; ?>">Manage Products</a>
            <a href="admindashboardusers.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardusers.php' ? 'font-bold text-black underline' : ''; ?>">Manage Users</a>
            <a href="logout.php" class="block px-3 py-2 bg-black text-white rounded text-center hover:bg-gray-800">Logout</a>
        </div>
    </nav>

    <!-- Main content section -->
    <section class="my-8 px-4">
        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold pb-6">Customer Orders</h2>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="overflow-x-auto">
                <!-- Orders table -->
                <table class="min-w-full border border-black bg-white rounded-lg shadow-md">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">User ID</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Unit Price (LKR)</th>
                            <th class="px-4 py-2 text-left">Total (LKR)</th>
                            <th class="px-4 py-2 text-left">Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Looping each order -->
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-t border-black hover:bg-gray-50">
                                <td class="px-4 py-2"><?php echo $order['order_id']; ?></td>
                                <td class="px-4 py-2"><?php echo $order['user_id']; ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($order['email']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($order['product']); ?></td>
                                <td class="px-4 py-2"><?php echo $order['quantity']; ?></td>
                                <td class="px-4 py-2"><?php echo number_format($order['unit_price'], 2); ?></td>
                                <td class="px-4 py-2"><?php echo number_format($order['total_price'], 2); ?></td>
                                <td class="px-4 py-2"><?php echo $order['order_date']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t-4 py-8 px-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
        <div>
            <h3 class="font-semibold mb-2">Socials</h3>
            <div class="flex gap-4">
                <img src="img/whatsapp.png" alt="whatsapp" class="size-6" />
                <img src="img/instagram.png" alt="instagram" class="size-6" />
                <img src="img/tik-tok.png" alt="tiktok" class="size-6" />
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