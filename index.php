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

// Get all products and cart items for current user
$allProducts = getAllProducts();
$cartItems = getCartItems(); 
$cartCount = count($cartItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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

            <!-- Mobile hamburger menu -->
            <button id="menu-button" class="md:hidden">
                <img src="img/menu.png" alt="menu" class="w-6 h-6">
            </button>

            <!-- Desktop navigation links -->
            <div class="hidden md:flex md:flex-1 md:items-center md:justify-between w-full">
                <div class="flex justify-center space-x-6 mx-auto">
                    <!-- Highlight active page-->
                    <a href="index.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'font-bold text-black underline' : ''; ?>">Home</a>
                    <a href="about.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'font-bold text-black underline' : ''; ?>">About Us</a>
                    <a href="products.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'font-bold text-black underline' : ''; ?>">Products</a>
                    <a href="membership.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'font-bold text-black underline' : ''; ?>">Membership</a>
                </div>

                <!-- Icons -->
                <div class="flex items-center space-x-6">
                    <a href="userprofile.php">
                        <img src="img/user.png" alt="user" class="w-6 h-6 hover:opacity-75">
                    </a>
                    <a href="cart.php" class="relative">
                        <img src="img/cart.png" class="w-6 h-6">
                        <span class="absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-1"><?= $cartCount ?></span>
                    </a>
                    <a href="logout.php" class="bg-black text-white px-3 py-1 text-sm rounded hover:bg-gray-800">Logout</a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-4">
            <a href="index.php" class="block px-3 py-2 hover:bg-gray-200 <?= activeLink('index.php') ?>">Home</a>
            <a href="about.php" class="block px-3 py-2 hover:bg-gray-200 <?= activeLink('about.php') ?>">About Us</a>
            <a href="products.php" class="block px-3 py-2 hover:bg-gray-200 <?= activeLink('products.php') ?>">Products</a>
            <a href="membership.php" class="block px-3 py-2 hover:bg-gray-200 <?= activeLink('membership.php') ?>">Membership</a>
            <a href="userprofile.php" class="block px-3 py-2 hover:bg-gray-200">Profile</a>
            <a href="cart.php" class="block px-3 py-2 hover:bg-gray-200">Cart (<?= $cartCount ?>)</a>
            <a href="logout.php" class="block px-3 py-2 bg-black text-white rounded text-center hover:bg-gray-800">Logout</a>
        </div>
    </nav>

    <!-- Main Image -->
    <section class="relative">
        <img src="img/main-img.png" alt="main image" class="w-full object-cover md:h-[26rem]">
    </section>

    <!-- Advertisements -->
    <section class="flex justify-center gap-4 my-8 px-4">
        <div class="relative w-full h-24 md:w-80 md:h-32">
            <img src="img/ad-1.jpg" alt="advertisement1" class="w-full h-full object-cover rounded">
            <div class="absolute top-2 left-2 font-semibold text-sm p-2 md:text-lg md:p-4">
                Charm Rings
            </div>
        </div>
        <div class="relative w-full h-24 md:w-80 md:h-32">
            <img src="img/ad-2.png" alt="advertisement1" class="w-full h-full object-cover rounded">
            <div class="absolute top-2 left-2 font-semibold text-sm p-2 md:text-lg md:p-4">
                Elegant Bracelets
            </div>
        </div>
        <div class="relative w-full h-24 md:w-80 md:h-32">
            <img src="img/products/butterfly-pearl-earring.jpeg" alt="advertisement1" class="w-full h-full object-cover rounded">
            <div class="absolute top-2 left-2 font-semibold text-sm p-2 md:text-lg md:p-4">
                Modern Earrings
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="text-center my-6">
        <h2 class="text-xl font-semibold mb-4">Popular Categories</h2>
        <div class="flex justify-center gap-6 flex-wrap">
            <a href="products.php" class="flex flex-col items-center">
                <img src="img/products/flower-ring.png" alt="Rings" class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Rings</span>
            </a>

            <a href="products.php" class="flex flex-col items-center">
                <img src="img/products/gem-earring.png" alt="Earrings" class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Earrings</span>
            </a>

            <a href="products.php" class="flex flex-col items-center">
                <img src="img/products/pearl-flower-bracelet.png" alt="Bracelets" class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Bracelets</span>
            </a>

            <a href="products.php" class="flex flex-col items-center">
                <img src="img/products/diamond-necklace.png" alt="Necklaces" class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full mb-2 hover:opacity-80 transition">
                <span class="text-sm font-medium">Necklaces</span>
            </a>
        </div>
    </section>

    <!-- Trendy Collection -->
    <section class="text-center my-8 px-8">
        <div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h2 class="text-xl font-semibold">Trendy Collection</h2>
            <a href="products.php" class="text-xs md:text-sm bg-black text-white px-3 py-2 rounded hover:bg-gray-800 self-end mt-2">View More >></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php
            // Pick 5 random products
            $randomProducts = $allProducts;
            shuffle($randomProducts);
            $randomProducts = array_slice($randomProducts, 0, 5);

            // Display 5 random products
            foreach ($randomProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <img src="<?php echo htmlspecialchars($product['image_url']) ?>" alt="<?php echo htmlspecialchars($product['name']) ?>" class="w-full h-24 md:h-48 object-cover rounded">
                    <p class="mt-2 font-medium"><?php echo htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?php echo number_format($product['price'], 2) ?></p>
                    <form method="POST" action="route.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Section -->
    <section class="flex items-center justify-center my-12 px-6">
        <div class="flex w-full max-w-6xl">
            <div class="flex items-center justify-center md:w-1/2 ">
                <img src="img/login-ad.jpg" alt="About Us" class="hidden md:block md:w-full md:h-[26rem] object-cover">
            </div>
            <div class="flex flex-col justify-center px-6 text-center md:w-1/2 md:text-left">
                <h2 class="text-2xl font-semibold mb-4">About Zyra</h2>
                <p class="mb-4">Discover jewelry that tells your story. At Zyra, we craft pieces that blend elegance, meaning, and modern design — perfect for every style and every moment.</p>
                <a href="about.php" class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800 self-center md:self-start">Learn More</a>
            </div>
        </div>
    </section>

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