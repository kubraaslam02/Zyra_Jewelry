<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user']['usertype'] === 'admin') {
    header("Location: admindashboardorders.php");
    exit();
}

require_once "functions.php";

$allProducts = getAllProducts();
$ringProducts = getProductsByCategory('rings');
$earringProducts = getProductsByCategory('earrings');
$braceletProducts = getProductsByCategory('bracelets');
$necklaceProducts = getProductsByCategory('necklaces');
$cartItems = getCartItems(); 
$cartCount = count($cartItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
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
            <a href="index.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'font-bold text-black underline' : ''; ?>">Home</a>
            <a href="about.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'font-bold text-black underline' : ''; ?>">About Us</a>
            <a href="products.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'font-bold text-black underline' : ''; ?>">Products</a>
            <a href="membership.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'font-bold text-black underline' : ''; ?>">Membership</a>
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

    <!-- Special Offer -->
    <section class="my-8 px-8">
        <div class="flex justify-center items-center mb-4">
            <h2 class="text-3xl font-bold text-center">Special Offer</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php
            // Pick 5 random products
            $randomOfferProducts = $allProducts;
            shuffle($randomOfferProducts);
            $randomOfferProducts = array_slice($randomOfferProducts, 0, 5);

            foreach ($randomOfferProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded">
                    <p class="mt-2 font-medium"><?= htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="route.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Rings-->
    <section class="my-8 px-8">
        <div class="flex justify-center items-center mb-4">
            <h2 class="text-3xl font-bold text-center">Rings</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($ringProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded">
                    <p class="mt-2 font-medium"><?= htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="route.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Earrings-->
    <section class="my-8 px-8">
        <div class="flex justify-center items-center mb-4">
            <h2 class="text-3xl font-bold text-center">Earrings</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($earringProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded">
                    <p class="mt-2 font-medium"><?= htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="route.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Bracelets-->
    <section class="my-8 px-8">
        <div class="flex justify-center items-center mb-4">
            <h2 class="text-3xl font-bold text-center">Bracelets</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($braceletProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded">
                    <p class="mt-2 font-medium"><?= htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="route.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Necklaces-->
    <section class="my-8 px-8">
        <div class="flex justify-center items-center mb-4">
            <h2 class="text-3xl font-bold text-center">Necklaces</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($necklaceProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded">
                    <p class="mt-2 font-medium"><?= htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="route.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

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

    <script src="script.js"></script>

</body>
</html>