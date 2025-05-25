<?php
session_start();

// Redirect unauthenticated users to login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Redirect admin users to admin dashboard
if ($_SESSION['user']['usertype'] === 'admin') {
    header("Location: admindashboardorders.php");
    exit();
}

require_once "functions.php";

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch cart details
// Get full product info for items in the cart
$cartItems = getCartItems();
// Number of items in the cart
$cartCount = count($cartItems);
// Total cost of items           
$subtotal = calculateCartSubtotal();      
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Cart</title>
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
        <button id="menu-button" class="md:hidden">
            <img src="img/menu.png" alt="menu" class="w-6 h-6">
        </button>

        <!-- Navigation links for desktop -->
        <div class="hidden md:flex w-full justify-between items-center">
            <div class="flex justify-center space-x-6 mx-auto">
                <a href="index.php" class="px-3 py-2 hover:bg-gray-200">Home</a>
                <a href="about.php" class="px-3 py-2 hover:bg-gray-200">About Us</a>
                <a href="products.php" class="px-3 py-2 hover:bg-gray-200">Products</a>
                <a href="membership.php" class="px-3 py-2 hover:bg-gray-200">Membership</a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="userprofile.php"><img src="img/user.png" class="w-6 h-6 hover:opacity-75"></a>
                <a href="cart.php" class="relative">
                    <img src="img/cart.png" class="w-6 h-6">
                    <span class="absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-1"><?php echo $cartCount ?></span>
                </a>
                <a href="logout.php" class="bg-black text-white px-3 py-1 text-sm rounded hover:bg-gray-800">Logout</a>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-4">
        <a href="index.php" class="block px-3 py-2 hover:bg-gray-200">Home</a>
        <a href="about.php" class="block px-3 py-2 hover:bg-gray-200">About Us</a>
        <a href="products.php" class="block px-3 py-2 hover:bg-gray-200">Products</a>
        <a href="membership.php" class="block px-3 py-2 hover:bg-gray-200">Membership</a>
        <a href="userprofile.php" class="block px-3 py-2 hover:bg-gray-200">Profile</a>
        <a href="cart.php" class="block px-3 py-2 hover:bg-gray-200">Cart (<?php echo $cartCount ?>)</a>
        <a href="logout.php" class="block px-3 py-2 bg-black text-white text-center rounded hover:bg-gray-800">Logout</a>
    </div>
</nav>

<!-- Cart Section -->
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-center mb-8">My Cart</h1>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-6">
            <!-- If the cart is empty, show message -->
            <?php if (empty($cartItems)): ?>
                <p class="text-center text-gray-600">Your cart is empty.</p>

            <!-- If the cart has items, loop through them -->
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="flex justify-between items-center border-b pb-4 flex-wrap">
                        <!-- Item Info -->
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <img src="<?php echo htmlspecialchars($item['image_url']) ?>" alt="<?php echo htmlspecialchars($item['name']) ?>" class="w-20 h-20 object-cover rounded">
                            <div class="flex flex-col sm:flex-row justify-between w-full sm:items-center">
                                <p class="font-semibold"><?php echo htmlspecialchars($item['name']) ?></p>
                                <p class="text-gray-600">LKR <?php echo number_format($item['price'], 2) ?></p>
                            </div>
                        </div>

                        <!-- Quantity update buttons -->
                        <form action="route.php" method="post" class="flex items-center gap-2 mt-2">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $item['id'] ?>">
                            <!-- Decrease quantity button -->
                            <button type="submit" name="quantity" value="<?php echo $item['quantity'] - 1 ?>" class="bg-gray-300 px-2 rounded">−</button>
                            <!-- Show current quantity -->
                            <span><?php echo $item['quantity'] ?></span>
                            <!-- Increase quantity button -->
                            <button type="submit" name="quantity" value="<?php echo $item['quantity'] + 1 ?>" class="bg-gray-300 px-2 rounded">+</button>
                        </form>

                        <!-- Total for Item -->
                        <p class="text-center">LKR <?php echo number_format($item['price'] * $item['quantity'], 2) ?></p>

                        <!-- Remove item button -->
                        <form action="route.php" method="post">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $item['id'] ?>">
                            <button class="text-lg text-red-500 font-bold px-2">×</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-100 p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

            <!-- Looping each item in the cart -->
            <?php foreach ($cartItems as $item): ?>
                <div class="flex justify-between mb-2">
                    <span><?php echo htmlspecialchars($item['name']) ?></span>
                    <span>LKR <?php echo number_format($item['price'] * $item['quantity'], 2) ?></span>
                </div>
            <?php endforeach; ?>

            <hr class="my-4">
            <!-- Display subtotal -->
            <div class="flex justify-between font-bold text-lg">
                <span>Subtotal</span>
                <span>LKR <?php echo number_format($subtotal, 2) ?></span>
            </div>

            <form action="checkout.php" method="get">
                <button type="submit" class="mt-6 w-full bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 rounded-lg text-lg">
                    CHECKOUT
                </button>
            </form>
        </div>
    </div>

    <!-- Order History -->
    <div class="mt-10 text-center">
        <a href="orders.php" class="bg-gray-300 px-6 py-2 rounded-full font-semibold">Order History</a>
    </div>
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