<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Redirect admin users to the admin dashboard
if ($_SESSION['user']['usertype'] === 'admin') {
    header("Location: admindashboardorders.php");
    exit();
}

require_once "functions.php";

// Get all cart items for the logged-in user and count them for cart badge
$cartItems = getCartItems(); 
$cartCount = count($cartItems);

// Get the relevant users past orders
$orders = getUserOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">
    <!-- Navbar -->
    <nav class="bg-white shadow px-4 py-2">
        <div class="flex items-center justify-between md:justify-start">
            <!-- Logo -->
            <a href="index.php" class="mr-auto">
                <img src="img/logo.png" alt="logo" class="w-10 h-10 md:w-32 md:h-32">
            </a>

            <!-- Hamburger menu for mobile -->
            <button id= "menu-button"class="md:hidden">
                <img src="img/menu.png" alt="menu" class="w-6 h-6">
            </button>

            <!-- Desktop menu -->
            <div class="hidden md:flex md:flex-1 md:items-center md:justify-between w-full">
                <div class="flex justify-center space-x-6 mx-auto">
                    <a href="index.php" class="px-3 py-2 hover:bg-gray-200">Home</a>
                    <a href="about.php" class="px-3 py-2 hover:bg-gray-200">About Us</a>
                    <a href="products.php" class="px-3 py-2 hover:bg-gray-200">Products</a>
                    <a href="membership.php" class="px-3 py-2 hover:bg-gray-200">Membership</a>
                </div>

                <!-- Icons -->
                <div class="flex items-center space-x-6">
                    <a href="userprofile.php">
                        <img src="img/user.png" alt="user" class="w-6 h-6 hover:opacity-75">
                    </a>
                    <a href="cart.php" class="relative">
                        <img src="img/cart.png" class="w-6 h-6">
                        <span class="absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-1"><?php echo $cartCount ?></span>
                    </a>
                    <a href="logout.php" class="bg-black text-white px-3 py-1 text-sm rounded hover:bg-gray-800">Logout</a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-4">
            <a href="index.php" class="block px-3 py-2 hover:bg-gray-200">Home</a>
            <a href="about.php" class="block px-3 py-2 hover:bg-gray-200">About Us</a>
            <a href="products.php" class="block px-3 py-2 hover:bg-gray-200">Products</a>
            <a href="membership.php" class="block px-3 py-2 hover:bg-gray-200">Membership</a>
            <a href="userprofile.php" class="block px-3 py-2 hover:bg-gray-200">Profile</a>
            <a href="cart.php" class="block px-3 py-2 hover:bg-gray-200">Cart (<?php echo $cartCount ?>)</a>
            <a href="logout.php" class="block px-3 py-2 bg-black text-white rounded text-center hover:bg-gray-800">Logout</a>
        </div>
    </nav>

    <!-- All past orders -->
    <div class="p-12">
        <h1 class="text-2xl font-semibold mb-6">Your Orders</h1>
        <!-- Loop through each order -->
        <?php foreach ($orders as $order): ?>
            <div class="bg-gray-300 rounded-xl p-4 mb-6">
                <div class="flex flex-wrap justify-between text-sm font-medium mb-2 p-6">
                    <div>Order ID: <?php echo $order['id']; ?></div>
                    <div>Total Amount (LKR): <?php echo htmlspecialchars($order['total']) ?></div>
                    <div>Delivered to: <?php echo htmlspecialchars($order['address']) ?></div>
                    <div>Order Date: <?php echo htmlspecialchars($order['order_date']) ?></div>
                </div>
                <div class="border-t border-gray-400 mt-2 pt-3 text-sm md:p-6">
                    <div class="mb-3 font-semibold">Delivered: <?php echo htmlspecialchars($order['delivery_date']) ?></div>
                    <div class="grid grid-cols-2 gap-4 md:flex md:gap-6 md:flex-wrap">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="flex flex-col items-center w-28 text-center">
                                <img src="<?php echo htmlspecialchars($item['image_url']) ?>" alt="<?php echo htmlspecialchars($item['name']) ?>" class="w-20 h-20 rounded-md mb-2 object-cover">
                                <div class="text-sm font-medium"><?php echo htmlspecialchars($item['name']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <footer class="border-t-4 py-8 px-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
        <div>
            <h3 class="font-semibold mb-2">Contact Us</h3>
            <p>Address: 116/A, Nawala Road, Sri Lanka.</p>
            <p>Email: zyrajewelry@gmail.com</p>
            <p>Phone: +94 11 258 4598</p>
        </div>
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
            <a href="index.php" class="block underline">Home</a>
            <a href="about.php" class="block underline">About Us</a>
            <a href="products.php" class="block underline">Products</a>
            <a href="membership.php" class="block underline">Membership</a>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Join the Mailing List</h3>
            <form>
                <input type="email" placeholder="Enter your email" class="w-full border px-2 py-1 mb-2">
                <button type="submit" class="bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Subscribe</button>
            </form>
        </div>
    </footer>
    <script src="main.js" defer></script>
</body>
</html>