<?php
session_start();
include "functions.php";

try {
    if (isset($_POST['login'])) {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (loginUser($email, $password)) {
            $usertype = $_SESSION['user']['usertype'] ?? 'user'; // default to 'user' if not set
        
            if ($usertype === 'admin') {
                header("Location: admindashboardorders.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }    
    }

    elseif (isset($_POST['signup'])) {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: signup.php");
            exit();
        }

        registerUser($username, $email, $password);
        $_SESSION['success'] = "Account created successfully!";
        header("Location: login.php");
        exit();
    }

    elseif (isset($_POST['action'])) {
        $action = $_POST['action'];
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if ($action === 'add' && $productId) {
            addToCart($productId, $quantity);
        } elseif ($action === 'update' && $productId) {
            updateQuantity($productId, $quantity);
        } elseif ($action === 'remove' && $productId) {
            removeFromCart($productId);
        }

        $referrer = $_SERVER['HTTP_REFERER'] ?? 'index.php'; // Redirect back to where the user came from
        header("Location: $referrer");
        exit();
    }

    elseif (isset($_POST['place_order'])) {
        $userId = $_SESSION['user']['id'];

        // Billing Info
        $email = $_POST['email'];
        $address = $_POST['address'];

        // Payment Info
        $paymentMethod = $_POST['payment_method'];

        // Cart details
        $cartItems = getCartItems();
        $subtotal = calculateCartSubtotal();

        // Insert into orders table
        $orderDate = date('Y-m-d');
        $deliveryDate = date('Y-m-d', strtotime('+14 days'));

        $conn = connectDB(); // assumes function from functions.php
        $stmt = $conn->prepare("INSERT INTO orders (user_id, email, address, order_date, delivery_date, total, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $email, $address, $orderDate, $deliveryDate, $subtotal, $paymentMethod]);
        $orderId = $conn->lastInsertId();

        // Insert items into order_items
        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $stmtItem->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
        }

        // Clear the cart
        clearCart();
        $_SESSION['thank_you'] = "Thank you for your purchase! Your order will be delivered by " . date('F j, Y', strtotime('+14 days')) . ".";

        header("Location: checkout.php");
        exit();
    }

    else {
        header("Location: login.php");
        exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: login.php");
    exit();
}