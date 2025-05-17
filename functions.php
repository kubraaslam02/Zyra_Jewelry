<?php
function connectDB() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=zyrajewelry_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}

function loginUser($email, $password) {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user['email'];
        return true;
    }
    return false;
}

function registerUser($username, $email, $password) {
    $pdo = connectDB();

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception("Email already registered.");
    }

    // Store password
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);
    return true;
}

function getAllProducts() {
    $pdo = connectDB();
    $stmt = $pdo->query("SELECT * FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductsByCategory($category) {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->execute([$category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//CartPage

function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }

    $userEmail = $_SESSION['user'];
    $pdo = connectDB();

    // Check if product already in cart
    $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE user_email = ? AND product_id = ?");
    $stmt->execute([$userEmail, $productId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update quantity (add to existing)
        $newQty = $existing['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_email = ? AND product_id = ?");
        $stmt->execute([$newQty, $userEmail, $productId]);
    } else {
        // Insert new cart item
        $stmt = $pdo->prepare("INSERT INTO cart (user_email, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$userEmail, $productId, $quantity]);
    }
}

function updateQuantity($productId, $quantity) {
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }
    $userEmail = $_SESSION['user'];
    $pdo = connectDB();

    if ($quantity > 0) {
        // Update quantity
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_email = ? AND product_id = ?");
        $stmt->execute([$quantity, $userEmail, $productId]);
    } else {
        // Remove item if quantity <= 0
        removeFromCart($productId);
    }
}

function removeFromCart($productId) {
    if (!isset($_SESSION['user'])) {
        throw new Exception("User not logged in.");
    }
    $userEmail = $_SESSION['user'];
    $pdo = connectDB();

    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_email = ? AND product_id = ?");
    $stmt->execute([$userEmail, $productId]);
}

function getCartItems() {
    if (!isset($_SESSION['user'])) {
        return [];
    }
    $userEmail = $_SESSION['user'];
    $pdo = connectDB();

    // Join cart with products to get product info + quantity
    $stmt = $pdo->prepare("
        SELECT p.*, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_email = ?
    ");
    $stmt->execute([$userEmail]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function calculateCartSubtotal() {
    $items = getCartItems();
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal;
}