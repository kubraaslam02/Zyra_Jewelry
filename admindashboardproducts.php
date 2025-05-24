<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user']['usertype'] === 'user') {
    header("Location: index.php");
    exit();
}

require_once "functions.php";

// Fetch products from DB
$products = getAllProducts();

// Flash messages
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;

// Clear messages after showing once
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">
    <!-- Navbar -->
    <nav class="bg-white shadow px-4 py-2">
        <div class="flex items-center justify-between md:justify-start">
            <!-- Logo -->
            <a href="admindashboardorders.php" class="mr-auto">
                <img src="img/logo.png" alt="logo" class="w-10 h-10 md:w-32 md:h-32">
            </a>

            <!-- Hamburger for mobile -->
            <button id= "menu-button"class="md:hidden">
                <img src="img/menu.png" alt="menu" class="w-6 h-6">
            </button>

            <!-- Desktop Only -->
            <div class="hidden md:flex md:flex-1 md:items-center md:justify-between w-full">
                <!-- Centered Links -->
                <div class="flex justify-center space-x-6 mx-auto">
                    <a href="admindashboardorders.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardorders.php' ? 'font-bold text-black underline' : ''; ?>">View Orders</a>
                    <a href="admindashboardproducts.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardproducts.php' ? 'font-bold text-black underline' : ''; ?>">Manage Products</a>
                    <a href="admindashboardusers.php" class="px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardusers.php' ? 'font-bold text-black underline' : ''; ?>">Manage Users</a>
                </div>

                <div class="flex items-center space-x-8 px-6">
                    <a href="logout.php" class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800">Logout</a>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-4">
            <a href="admindashboardorders.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardorders.php' ? 'font-bold text-black underline' : ''; ?>">View Orders</a>
            <a href="admindashboardproducts.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardproducts.php' ? 'font-bold text-black underline' : ''; ?>">Manage Products</a>
            <a href="admindashboardusers.php" class="block px-3 py-2 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardusers.php' ? 'font-bold text-black underline' : ''; ?>">Manage Users</a>
            <a href="logout.php" class="block px-3 py-2 bg-black text-white rounded text-center hover:bg-gray-800">Logout</a>
        </div>
    </nav>

    <section class="my-8 px-4">
        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold pb-6">Available Products</h2>
        </div>

        <div class="max-w-7xl mx-auto">
            <?php if ($error): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Product List Table -->
            <div class="overflow-x-auto bg-white shadow rounded mb-8">
                <table class="min-w-full border border-black">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-6 py-3">Product ID</th>
                            <th class="px-6 py-3">Product Image</th>
                            <th class="px-6 py-3">Product Name</th>
                            <th class="px-6 py-3">Category</th>
                            <th class="px-6 py-3">Unit Price (LKR)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-black text-center">
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="px-6 py-4"><?= htmlspecialchars($product['id']) ?></td>
                                <td class="px-6 py-4 justify-items-center">
                                    <?php if ($product['image_url']): ?>
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image" class="h-16 w-16 object-cover rounded" />
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($product['name']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($product['category']) ?></td>
                                <td class="px-6 py-4"><?= number_format($product['price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 ">

                <!-- Add Product Form -->
                <section class="bg-white p-6 border border-black rounded shadow">
                    <h2 class="text-xl font-semibold mb-4">Add Product</h2>
                    <form action="route.php" method="POST" enctype="multipart/form-data" class="space-y-2">
                        <label for="product_name">Product Name:</label>
                        <input type="text" name="name" required class="w-full border border-gray-300 p-2 rounded">

                        <label for="unit_price">Unit Price (LKR):</label>
                        <input type="number" step="0.01" name="price" required class="w-full border border-gray-300 p-2 rounded">
                        
                        <label for="category">Category:</label>
                        <select name="category" required
                            class="w-full border border-gray-300 p-2 rounded">
                            <option value=""></option>
                            <option>Ring</option>
                            <option>Earring</option>
                            <option>Necklace</option>
                            <option>Bracelet</option>
                        </select>

                        <label for="file">Insert File:</label>
                        <input type="file" name="image" accept="img/*" required class="w-full border border-gray-300 p-2 rounded">
                        <div class="flex justify-center mt-4">
                            <button type="submit" name="add_product" class="bg-black hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Add Product
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Update Product Form -->
                <section class="bg-white p-6 border border-black rounded shadow">
                    <h2 class="text-xl font-semibold mb-4">Update Product</h2>
                    <form action="route.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <label for="product_name">Product Name:</label>
                        <input type="text" name="name" required class="w-full border border-gray-300 p-2 rounded">

                        <label for="unit_price">Unit Price (LKR):</label>
                        <input type="number" step="0.01" name="price" required class="w-full border border-gray-300 p-2 rounded">
                        
                        <label for="category">Category:</label>
                        <select name="category" required
                            class="w-full border border-gray-300 p-2 rounded">
                            <option value=""></option>
                            <option>Ring</option>
                            <option>Earring</option>
                            <option>Necklace</option>
                            <option>Bracelet</option>
                        </select>

                        <label for="file">Insert File:</label>
                        <input type="file" name="image" accept="img/*" class="w-full border border-gray-300 p-2 rounded">
                        <div class="flex justify-center mt-4">
                            <button type="submit" name="update_product" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Update Product
                            </button>
                        </div>
                    </form>
                </section>

            </div>

            <!-- Delete Product Form -->
            <section class="bg-white border border-black p-6 rounded shadow mt-8 max-w-md">
                <h2 class="text-xl font-semibold mb-4">Delete Product</h2>
                <form action="route.php" method="POST" class="space-y-4">
                    <label for="product_id">Product ID: </label>
                    <input type="text" name="delete_id" required class="w-full border border-gray-300 p-2 rounded">
                    <button type="submit" name="delete_product" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Delete Product
                    </button>
                </form>
            </section>

        </div>
        
    </section>

    <!-- Footer -->
    <footer class="border-t-4 py-8 px-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
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
            <a href="admindashboardorders.php" class="block underline">View Orders</a>
            <a href="admindashboardproducts.php" class="block underline">Manage Products</a>
            <a href="admindashboardusers.php" class="block underline">Manage Users</a>
        </div>
    </footer>

    <script src="main.js" defer></script>

</body>
</html>