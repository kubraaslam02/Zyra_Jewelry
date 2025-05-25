<?php
session_start();

// Redirect user to login page if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// If user is admin, redirect to admin dashboard
if ($_SESSION['user']['usertype'] === 'admin') {
    header("Location: admindashboardorders.php");
    exit();
}

require_once "functions.php";

// Fetch all products from the database
$allProducts = getAllProducts();

// Fetch products by category for display
$ringProducts = getProductsByCategory('rings');
$earringProducts = getProductsByCategory('earrings');
$braceletProducts = getProductsByCategory('bracelets');
$necklaceProducts = getProductsByCategory('necklaces');

// Fetch items currently in the user's cart
$cartItems = getCartItems();
$cartCount = count($cartItems); // Count how many items in the cart for display badge
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">
    <!-- Navbar -->
    <nav class="bg-white shadow px-4 py-2">
        <div class="flex items-center justify-between md:justify-start">
            <!-- Logo -->
            <a href="index.php" class="mr-auto">
                <img src="img/logo.png" alt="logo" class="w-10 h-10 md:w-32 md:h-32" />
            </a>

            <!-- Hamburger menu button for mobile -->
            <button id="menu-button" class="md:hidden">
                <img src="img/menu.png" alt="menu" class="w-6 h-6" />
            </button>

            <!-- Desktop navigation links -->
            <div class="hidden md:flex md:flex-1 md:items-center md:justify-between w-full">
                <div class="flex justify-center space-x-6 mx-auto">
                    <!-- Highlight the active page -->
                    <a href="index.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'font-bold text-black underline' : ''; ?>">Home</a>
                    <a href="about.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'font-bold text-black underline' : ''; ?>">About Us</a>
                    <a href="products.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'font-bold text-black underline' : ''; ?>">Products</a>
                    <a href="membership.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'font-bold text-black underline' : ''; ?>">Membership</a>
                </div>

                <!-- Icons -->
                <div class="flex items-center space-x-6">
                    <a href="userprofile.php">
                        <img src="img/user.png" alt="user" class="w-6 h-6 hover:opacity-75" />
                    </a>
                    <a href="cart.php" class="relative">
                        <img src="img/cart.png" class="w-6 h-6" />
                        <span class="absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-1"><?php echo $cartCount ?></span>
                    </a>
                    <a href="logout.php" class="bg-black text-white px-3 py-1 text-sm rounded hover:bg-gray-800">Logout</a>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-4">
            <a href="index.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'font-bold text-black underline' : ''; ?>">Home</a>
            <a href="about.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'font-bold text-black underline' : ''; ?>">About Us</a>
            <a href="products.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'font-bold text-black underline' : ''; ?>">Products</a>
            <a href="membership.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'font-bold text-black underline' : ''; ?>">Membership</a>
            <a href="userprofile.php" class="block px-3 py-2 hover:bg-gray-200">Profile</a>
            <a href="cart.php" class="block px-3 py-2 hover:bg-gray-200">Cart (<?php echo $cartCount ?>)</a>
            <a href="logout.php" class="block px-3 py-2 bg-black text-white rounded text-center hover:bg-gray-800">Logout</a>
        </div>
    </nav>

    <!-- Special Offer Section -->
    <section class="my-8 px-8">
        <div class="flex justify-center items-center mb-4">
            <h2 class="text-3xl font-bold text-center">Special Offer</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <?php
            // Shuffle all products and take 5 randomly
            $randomOfferProducts = $allProducts;
            shuffle($randomOfferProducts);
            $randomOfferProducts = array_slice($randomOfferProducts, 0, 5);

            // Loop through the 5 products to display each
            foreach ($randomOfferProducts as $product): ?>
                <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                    <!-- Product image -->
                    <img src="<?php echo htmlspecialchars($product['image_url']) ?>" alt="<?php echo htmlspecialchars($product['name']) ?>" class="w-full h-32 md:h-48 object-cover rounded" />
                    <!-- Product name and price -->
                    <p class="mt-2 font-medium"><?php echo htmlspecialchars($product['name']) ?></p>
                    <p class="text-sm text-gray-600">LKR <?php echo number_format($product['price'], 2) ?></p>
                    <!-- Add to Cart form -->
                    <form method="POST" action="route.php" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add" />
                        <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>" />
                        <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Product sections by category -->
    <?php
    // Function to generate product grid for a category
    function displayProductSection($title, $products) {
        ?>
        <section class="my-8 px-8">
            <div class="flex justify-center items-center mb-4">
                <h2 class="text-3xl font-bold text-center"><?php echo htmlspecialchars($title); ?></h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php foreach ($products as $product): ?>
                    <div class="border rounded-lg p-3 shadow hover:shadow-lg transition">
                        <img src="<?php echo htmlspecialchars($product['image_url']) ?>" alt="<?php echo htmlspecialchars($product['name']) ?>" class="w-full h-32 md:h-48 object-cover rounded" />
                        <p class="mt-2 font-medium"><?php echo htmlspecialchars($product['name']) ?></p>
                        <p class="text-sm text-gray-600">LKR <?php echo number_format($product['price'], 2) ?></p>
                        <form method="POST" action="route.php" class="add-to-cart-form">
                            <input type="hidden" name="action" value="add" />
                            <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>" />
                            <button type="submit" class="mt-2 bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }

    // Display all categories
    displayProductSection('Rings', $ringProducts);
    displayProductSection('Earrings', $earringProducts);
    displayProductSection('Bracelets', $braceletProducts);
    displayProductSection('Necklaces', $necklaceProducts);
    ?>

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
                <img src="img/whatsapp.png" alt="whatsapp" class="size-6" />
                <img src="img/instagram.png" alt="instagram" class="size-6" />
                <img src="img/tik-tok.png" alt="tiktok" class="size-6" />
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
                <input type="email" placeholder="Enter your email" class="w-full border px-2 py-1 mb-2" />
                <button type="submit" class="bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Subscribe</button>
            </form>
        </div>
    </footer>
    <script src="main.js"></script>
</body>
</html>