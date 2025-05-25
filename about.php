<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Redirect admin user to admin dashboard orders page
if ($_SESSION['user']['usertype'] === 'admin') {
    header("Location: admindashboardorders.php");
    exit();
}

require_once "functions.php";

// Get all cart items for the logged-in user and count them for cart badge
$cartItems = getCartItems();
$cartCount = count($cartItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About Us</title>
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

            <!-- Hamburger button for mobile menu toggle -->
            <button id="menu-button" class="md:hidden">
                <img src="img/menu.png" alt="menu" class="w-6 h-6" />
            </button>

            <!-- Desktop menu (hidden on small screens) -->
            <div class="hidden md:flex md:flex-1 md:items-center md:justify-between w-full">
                <!-- Navigation links -->
                <div class="flex justify-center space-x-6 mx-auto">
                    <!-- Highlight active page-->
                    <a href="index.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'font-bold text-black underline' : ''; ?>">Home</a>
                    <a href="about.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'font-bold text-black underline' : ''; ?>">About Us</a>
                    <a href="products.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'font-bold text-black underline' : ''; ?>">Products</a>
                    <a href="membership.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'font-bold text-black underline' : ''; ?>">Membership</a>
                </div>

                <!-- Icons-->
                <div class="flex items-center space-x-6">
                    <a href="userprofile.php">
                        <img src="img/user.png" alt="user" class="w-6 h-6 hover:opacity-75" />
                    </a>
                    <a href="cart.php" class="relative">
                        <img src="img/cart.png" alt="cart" class="w-6 h-6" />
                        <!-- Cart count badge -->
                        <span class="absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-1"><?php echo $cartCount ?></span>
                    </a>
                    <a href="logout.php" class="bg-black text-white px-3 py-1 text-sm rounded hover:bg-gray-800">Logout</a>
                </div>
            </div>
        </div>

        <!-- Mobile menu highlighting the active page -->
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

    <!-- Main image -->
    <section class="relative">
        <img src="img/ad-1.jpg" alt="main image" class="w-full md:h-[26rem] object-cover" />
        <div class="absolute inset-0 flex items-center justify-center">
            <h1 class="text-black text-3xl md:text-7xl font-bold">About Us</h1>
        </div>
    </section>

    <!-- About Section -->
    <section class="flex items-center justify-center my-12 px-6">
        <div class="flex w-full md:max-w-6xl">
            <div class="md:w-1/3 flex items-center justify-center">
                <img src="img/signup-ad.jpg" alt="About Us" class="hidden md:block md:w-full md:h-[30rem] object-cover rounded" />
            </div>
            <!-- Content -->
            <div class="md:w-2/3 flex flex-col justify-center px-6 text-center md:text-left">
                <h2 class="text-2xl font-semibold mb-4">Our Story</h2>
                <p class="mb-4">
                    Zyra Jewelry was born out of a love for minimalist design and meaningful accessories. What started as a small creative project quickly evolved into a full-fledged brand dedicated to crafting pieces that balance modern trends with timeless appeal.
                    <br /><br />
                    Whether you're dressing up for an event or adding a touch of glam to your everyday look, our collections are made to complement your lifestyle.
                </p>

                <h2 class="text-2xl font-semibold mb-4">Our Mission</h2>
                <p class="mb-4">
                    We envision a world where everyone can access beauty, feel empowered, and wear jewelry that tells a story — your story.
                </p>

                <h2 class="text-2xl font-semibold mb-4">Our Vision</h2>
                <p class="mb-4">
                    Welcome to Zyra Jewelry — where timeless elegance meets everyday wear. Founded with a passion for beauty, craftsmanship, and self-expression, Zyra Jewelry is your destination for stylish, high-quality jewelry that makes a statement.
                </p>
            </div>
        </div>
    </section>

    <!-- Advertisements Section -->
    <section class="flex justify-center gap-4 my-8 px-4 flex-wrap">
        <div class="w-80">
            <img src="img/ad-1.jpg" alt="advertisement1" class="w-full h-32 object-cover" />
            <div class="text-lg text-center mt-2">Everyday Essentials</div>
        </div>
        <div class="w-80">
            <img src="img/ad-2.png" alt="advertisement2" class="w-full h-32 object-cover" />
            <div class="text-lg text-center mt-2">Occasion-ready Design</div>
        </div>
        <div class="w-80">
            <img src="img/products/butterfly-pearl-earring.jpeg" alt="advertisement3" class="w-full h-32 object-cover" />
            <div class="text-lg text-center mt-2">Custom & gift-worthy pieces</div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t-4 py-8 px-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
        <!-- Contact Info -->
        <div>
            <h3 class="font-semibold mb-2">Contact Us</h3>
            <p>Address: 116/A, Nawala Road, Sri Lanka.</p>
            <p>Email: zyrajewelry@gmail.com</p>
            <p>Phone: +94 11 258 4598</p>
        </div>

        <!-- Social Icons -->
        <div>
            <h3 class="font-semibold mb-2">Socials</h3>
            <div class="flex gap-4">
                <img src="img/whatsapp.png" alt="whatsapp" class="size-6" />
                <img src="img/instagram.png" alt="instagram" class="size-6" />
                <img src="img/tik-tok.png" alt="tiktok" class="size-6" />
            </div>
        </div>

        <!-- Quick Links -->
        <div>
            <h3 class="font-semibold mb-2">Quick Links</h3>
            <a href="index.php" class="block underline">Home</a>
            <a href="about.php" class="block underline">About Us</a>
            <a href="products.php" class="block underline">Products</a>
            <a href="membership.php" class="block underline">Membership</a>
        </div>

        <!-- Mailing List Subscription -->
        <div>
            <h3 class="font-semibold mb-2">Join the Mailing List</h3>
            <form>
                <input type="email" placeholder="Enter your email" class="w-full border px-2 py-1 mb-2" />
                <button type="submit" class="bg-black text-white px-4 py-1 text-sm rounded hover:bg-gray-600">Subscribe</button>
            </form>
        </div>
    </footer>
    <script src="main.js" defer></script>
</body>
</html>