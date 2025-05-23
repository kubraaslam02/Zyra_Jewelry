<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once "functions.php";
$cartItems = getCartItems(); 
$cartCount = count($cartItems);

$orders = getUserOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">
    <!-- Navbar -->
    <nav class="bg-white shadow flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <a href="index.php">
                <img src="img/logo.png" alt="logo" class="w-32 h-32">
            </a>
            <a href="index.php" class="px-3 py-1 hover:bg-gray-200">Home</a>
            <a href="about.php" class="px-3 py-1 hover:bg-gray-200">About Us</a>
            <a href="products.php" class="px-3 py-1 hover:bg-gray-200">Products</a>
            <a href="membership.php" class="px-3 py-1 hover:bg-gray-200">Membership</a>
        </div>
        <div class="flex items-center space-x-8 px-6">
            <a href="userprofile.php">
                <img src="img/user.png" alt="user" class="w-8 h-8 hover:opacity-75">
            </a>
            <a href="cart.php" class="relative">
                <img src="img/cart.png" class="w-8 h-8">
                <span class="absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-1"><?= $cartCount ?></span>
            </a>
            <a href="logout.php" class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800">Logout</a>
        </div>
    </nav>

    <div class="p-12">
        <h1 class="text-2xl font-semibold mb-6">Your Orders</h1>
        
        <?php foreach ($orders as $order): ?>
            <div class="bg-gray-300 rounded-xl p-4 mb-6">
                <div class="flex flex-wrap justify-between text-sm font-medium mb-2 p-6">
                    <div>Order ID: <?= htmlspecialchars($order['id']) ?></div>
                    <div>Total Amount (LKR): <?= htmlspecialchars($order['total']) ?></div>
                    <div>Delivered to: <?= htmlspecialchars($order['address']) ?></div>
                    <div>Order Date: <?= htmlspecialchars($order['order_date']) ?></div>
                </div>
                <div class="border-t border-gray-400 mt-2 pt-3 text-sm p-6">
                    <div class="mb-3 font-semibold">Delivered: <?= htmlspecialchars($order['delivery_date']) ?></div>
                    <div class="flex gap-6 flex-wrap">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="flex flex-col items-center w-28 text-center">
                                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-20 h-20 rounded-md mb-2 object-cover">
                                <div class="text-sm font-medium"><?= htmlspecialchars($item['name']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <footer class="border-t-4 py-8 px-16 grid grid-cols-4 gap-4 text-sm">
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
</body>
</html>