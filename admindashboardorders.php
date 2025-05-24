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

try {
    $pdo = connectDB();
    $stmt = $pdo->query("
        SELECT 
            o.id AS order_id,
            o.user_id,
            o.email,
            p.name AS product,
            oi.quantity,
            oi.unit_price,
            (oi.quantity * oi.unit_price) AS total_price,
            DATE_FORMAT(o.order_date, '%d/%m/%Y') AS order_date
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        ORDER BY o.order_date ASC
    ");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching orders: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-serif text-black">
    <!-- Navbar -->
    <nav class="bg-white shadow flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <a href="admindashboardorders.php">
                <img src="img/logo.png" alt="logo" class="w-32 h-32">
            </a>
            <a href="admindashboardorders.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardorders.php' ? 'font-bold text-black underline' : ''; ?>">View Orders</a>
            <a href="admindashboardproducts.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardproducts.php' ? 'font-bold text-black underline' : ''; ?>">Manage Products</a>
            <a href="admindashboardusers.php" class="px-3 py-1 hover:bg-gray-200 <?php echo basename($_SERVER['PHP_SELF']) == 'admindashboardusers.php' ? 'font-bold text-black underline' : ''; ?>">Manage Users</a>
        </div>
        <div class="flex items-center space-x-8 px-6">
            <a href="logout.php" class="bg-black text-white px-8 py-2 rounded hover:bg-gray-800">Logout</a>
        </div>
    </nav>

    <section class="my-8 px-4">
        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold pb-6">Customer Orders</h2>
        </div>
        <div class="max-w-7xl mx-auto">
            <div class="overflow-x-auto">
                <table class="min-w-full border border-black bg-white rounded-lg shadow-md">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">User ID</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Unit Price (LKR)</th>
                            <th class="px-4 py-2 text-left">Total (LKR)</th>
                            <th class="px-4 py-2 text-left">Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-t border-black hover:bg-gray-50">
                                <td class="px-4 py-2"><?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td class="px-4 py-2"><?php echo $order['user_id']; ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($order['email']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($order['product']); ?></td>
                                <td class="px-4 py-2"><?php echo $order['quantity']; ?></td>
                                <td class="px-4 py-2"><?php echo number_format($order['unit_price'], 2); ?></td>
                                <td class="px-4 py-2"><?php echo number_format($order['total_price'], 2); ?></td>
                                <td class="px-4 py-2"><?php echo $order['order_date']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t-4 py-8 px-16 grid grid-cols-4 gap-4 text-sm">
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
</body>
</html>