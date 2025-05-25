<?php

// DATABASE CONNECTION
// Establishes a database connection using PDO
function connectDB() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=zyrajewelry_db", "root", "");
        // Set the PDO error mode to "exception"
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}

// USER AUTHENTICATION
// Authenticates a user based on email and password
function loginUser($email, $password) {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

// Registers a new user if email is not already used
function registerUser($username, $email, $password) {
    $pdo = connectDB();

    // Check for duplicate email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception("Email already registered.");
    }

    // Insert new user to db
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);
    return true;
}

// Returns currently logged in user's ID.
function getUserId() {
    return $_SESSION['user']['id'] ?? null;
}

// USER MANAGEMENT
// Retrieves all users excluding admin
function getAllUsers() {
    $pdo = connectDB();
    $stmt = $pdo->query("SELECT * FROM users WHERE usertype != 'admin'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// PRODUCT MANAGEMENT
// Retrieves all products excluding membership options
function getAllProducts() {
    $pdo = connectDB();
    $stmt = $pdo->query("SELECT * FROM products WHERE category != 'membership'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Retrieves products by specific category
function getProductsByCategory($category) {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->execute([$category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handles product image upload and returns the file path
function uploadProductImage() {
    if (!empty($_FILES['image']['name'])) {
        // Set the target directory where images will be stored
        $targetDir = "img/products/";
        $filename = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $filename;

        // Move the uploaded file from the temporary location to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            return $targetFile;
        }
    }
    return '';
}

// CART MANAGEMENT
// Adds product to cart or updates quantity if it already exists
function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }

    $userEmail = $_SESSION['user']['email'];
    $pdo = connectDB();

    // Check if product is already in cart
    $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE user_email = ? AND product_id = ?");
    $stmt->execute([$userEmail, $productId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update quantity
        $newQty = $existing['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_email = ? AND product_id = ?");
        $stmt->execute([$newQty, $userEmail, $productId]);
    } else {
        // Add new item to cart
        $stmt = $pdo->prepare("INSERT INTO cart (user_email, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$userEmail, $productId, $quantity]);
    }
}

// Updates product quantity in the cart
function updateQuantity($productId, $quantity) {
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }

    $userEmail = $_SESSION['user']['email'];
    $pdo = connectDB();

    if ($quantity > 0) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_email = ? AND product_id = ?");
        $stmt->execute([$quantity, $userEmail, $productId]);
    } else {
        removeFromCart($productId); // If quantity is zero or less, item is removed
    }
}

// Removes a product from cart
function removeFromCart($productId) {
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }

    $userEmail = $_SESSION['user']['email'];
    $pdo = connectDB();

    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_email = ? AND product_id = ?");
    $stmt->execute([$userEmail, $productId]);
}

// Clears the user's entire cart
function clearCart() {
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }

    $userEmail = $_SESSION['user']['email'];
    $pdo = connectDB();

    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_email = ?");
    $stmt->execute([$userEmail]);
}

// Retrieves current user's cart items
function getCartItems() {
    if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || !isset($_SESSION['user']['email'])) {
        return [];
    }

    $userEmail = $_SESSION['user']['email'];
    $pdo = connectDB();

    // Join the 'cart' table and 'products' table to get full product details
    $stmt = $pdo->prepare("SELECT p.*, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_email = ?");
    $stmt->execute([$userEmail]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calculates subtotal of items in the cart
function calculateCartSubtotal() {
    $items = getCartItems();
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal;
}

// ORDER MANAGEMENT
// Get all orders made by the currently logged-in user
function getUserOrders() {
    if (!isset($_SESSION['user'])) {
        return [];
    }

    $pdo = connectDB();
    $userEmail = $_SESSION['user']['email'];

    // Get orders
    $ordersStmt = $pdo->prepare("SELECT * FROM orders WHERE email = ?");
    $ordersStmt->execute([$userEmail]);
    $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get items for each order
    foreach ($orders as &$order) {
        $orderId = $order['id'];
        // Join the 'order_items' table and 'products' table to get full order details
        $itemsStmt = $pdo->prepare("SELECT p.name, p.image_url, oi.quantity FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $itemsStmt->execute([$orderId]);
        $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $orders;
}

// Retrieves all orders (for admin)
function getAllOrders() {
    try {
        $pdo = connectDB();

        // Join the 'orders' table with 'order_items' table and 'products' table to get all the relevant order details
        $stmt = $pdo->query("SELECT o.id AS order_id, o.user_id, o.email, p.name AS product, oi.quantity, oi.unit_price, (oi.quantity * oi.unit_price) AS total_price, DATE_FORMAT(o.order_date, '%d/%m/%Y') AS order_date
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            ORDER BY o.order_date ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Error fetching orders: " . $e->getMessage());
    }
}