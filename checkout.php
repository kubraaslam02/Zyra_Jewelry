<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once "functions.php";
$cartItems = getCartItems(); 
$cartCount = count($cartItems);
$subtotal = calculateCartSubtotal();

$thankYouMessage = '';
if (isset($_SESSION['thank_you'])) {
    $thankYouMessage = $_SESSION['thank_you'];
    unset($_SESSION['thank_you']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php if (!empty($thankYouMessage)): ?>
<script>
    alert("<?= addslashes($thankYouMessage) ?>");
    window.location.href = "index.php";
</script>
<?php endif; ?>
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

    <div class="max-w-7xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <form action="route.php" method="POST" class="bg-gray-50 p-6 rounded-lg shadow space-y-4">
                    <h2 class="text-xl font-semibold mb-4">Billing Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm">First Name:</label>
                            <input type="text" name="first_name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm">Last Name:</label>
                            <input type="text" name="last_name" required class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm">Email:</label>
                        <input type="email" name="email" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm">Phone Number:</label>
                        <input type="tel" name="phone" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm">Address:</label>
                        <input type="text" name="address" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm">City:</label>
                            <input type="text" name="city" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm">Zip Code:</label>
                            <input type="text" name="zip" required class="w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <h2 class="text-xl font-semibold mt-6 mb-4">Payment Information</h2>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="card" checked>
                            <span class="ml-2">Credit / Debit Card</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="cod">
                            <span class="ml-2">Cash on Delivery</span>
                        </label>
                    </div>

                    <div class="space-y-4 mt-4" id="card-info">
                        <div>
                            <label class="block text-sm">Card Holder Name:</label>
                            <input type="text" name="card_name" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm">Card Number:</label>
                            <input type="text" name="card_number" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm">Month</label>
                                <select name="card_month" class="w-full border rounded px-2 py-2">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>"><?= str_pad($m, 2, '0', STR_PAD_LEFT) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm">Year</label>
                                <select name="card_year" class="w-full border rounded px-2 py-2">
                                    <?php for ($y = date("Y"); $y <= date("Y") + 10; $y++): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm">CVV</label>
                                <input type="text" name="card_cvv" maxlength="4" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="place_order" class="bg-gray-700 text-white font-semibold px-10 py-3 rounded-full hover:bg-gray-900">
                            Pay Now
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-gray-100 p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex justify-between mb-2">
                            <span><?= htmlspecialchars($item['name']) ?></span>
                            <span>LKR <?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                    <hr class="my-4">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Subtotal</span>
                        <span>LKR <?= number_format($subtotal, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
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