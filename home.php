<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once "functions.php";

$allProducts = getAllProducts();
$cartItems = getCartItems(); 
$cartCount = count($cartItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">
    <!-- Navbar -->
    <nav class="bg-white shadow flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <a href="home.php">
                <img src="img/logo.png" alt="logo" class="w-32 h-32">
            </a>
            <a href="home.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'font-bold text-black underline' : ''; ?>">Home</a>
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

    <!-- Main Image -->
    <section class="relative">
        <img src="img/main-img.jpg" alt="main image" class="w-full h-[26rem] object-cover">
        <div class="absolute inset-0 flex items-center justify-center">
            <h1 class="text-9xl text-center font-style: italic">Zyra<br>Jewelry</h1>
        </div>
    </section>

    <!-- Advertisements -->
    <section class="flex justify-center gap-4 my-8 px-4">
        <div class="relative w-80 h-32">
            <img src="img/ad-1.jpg" alt="advertisement1" class="w-full h-full object-cover rounded">
            <div class="absolute top-2 left-2 text-lg font-semibold px-4 py-4">
                Charm Rings
            </div>
        </div>
        <div class="relative w-80 h-32">
            <img src="img/ad-2.png" alt="advertisement1" class="w-full h-full object-cover rounded">
            <div class="absolute top-2 left-2 text-lg font-semibold px-4 py-4">
                Elegant Bracelets
            </div>
        </div>
        <div class="relative w-80 h-32">
            <img src="img/ad-3.jpeg" alt="advertisement1" class="w-full h-full object-cover rounded">
            <div class="absolute top-2 left-2 text-lg font-semibold px-4 py-4">
                Modern Earrings
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="text-center my-6">
        <h2 class="text-xl font-semibold mb-4">Popular Categories</h2>
        <div class="flex justify-center gap-6 flex-wrap">
            <a href="products.php" class="flex flex-col items-center">
                <img src="img/products/flower-ring.png" alt="Rings" class="w-24 h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Rings</span>
            </a>

            <a href="products.php" class="flex flex-col items-center">
                <img src="img/cat-2.png" alt="Earrings" class="w-24 h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Earrings</span>
            </a>

            <a href="products.php" class="flex flex-col items-center">
                <img src="img/cat-3.png" alt="Bracelets" class="w-24 h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Bracelets</span>
            </a>

            <a href="products.php" class="flex flex-col items-center">
                <img src="img/cat-4.png" alt="Necklaces" class="w-24 h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Necklaces</span>
            </a>
        </div>
    </section>

    <!-- Trendy Collection -->
    <section class="my-8 px-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Trendy Collection</h2>
            <a href="products.php" class="text-sm bg-black text-white px-3 py-2 rounded hover:bg-gray-800">View More >></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php foreach ($allProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded">
                    <p class="mt-2 font-medium"><?= htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="route.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Zyra -->
    <section class="flex items-center justify-center my-12 px-6">
        <div class="flex w-full max-w-6xl">
            <div class="w-1/2 flex items-center justify-center">
                <img src="img/login-ad.jpg" alt="About Us" class="w-full h-[26rem] object-cover">
            </div>
            <div class="w-1/2 flex flex-col justify-center px-6">
                <h2 class="text-2xl font-semibold mb-4">About Zyra</h2>
                <p class="mb-4">Discover jewelry that tells your story. At Zyra, we craft pieces that blend elegance, meaning, and modern design — perfect for every style and every moment. Whether you're searching for a timeless gift or a bold new look, Zyra is where your shine begins.</p>
                <a href="about.php" class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800 self-start">Learn More</a>
            </div>
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
            <a href="home.php" class="block underline">Home</a>
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