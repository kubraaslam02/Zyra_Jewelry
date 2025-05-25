<?php
session_start();
include "functions.php";

try {
    // USER LOGIN
    if (isset($_POST['login'])) {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Authenticate user
        if (loginUser($email, $password)) {
            $usertype = $_SESSION['user']['usertype'] ?? 'user'; // Default to 'user' if not set
            
            // Redirect based on user type
            if ($usertype === 'admin') {
                header("Location: admindashboardorders.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    }

    // USER REGISTRATION
    elseif (isset($_POST['signup'])) {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Check for empty fields
        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: signup.php");
            exit();
        }

        // Register new user
        registerUser($username, $email, $password);
        $_SESSION['success'] = "Account created successfully!";
        header("Location: login.php");
        exit();
    }

    // CART ACTIONS: Add, Update, Remove
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

        // Redirect back to previous page
        $referrer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header("Location: $referrer");
        exit();
    }

    // PLACE ORDER
    elseif (isset($_POST['place_order'])) {
        $userId = $_SESSION['user']['id'];

        // Get billing and payment info
        $email = $_POST['email'];
        $address = $_POST['address'];
        $paymentMethod = $_POST['payment_method'];

        // Get cart data
        $cartItems = getCartItems();
        $subtotal = calculateCartSubtotal();
        $orderDate = date('Y-m-d');
        $deliveryDate = date('Y-m-d', strtotime('+14 days'));

        $conn = connectDB();

        // Insert to orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, email, address, order_date, delivery_date, total, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $email, $address, $orderDate, $deliveryDate, $subtotal, $paymentMethod]);
        $orderId = $conn->lastInsertId();

        // Insert to order_items table
        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $stmtItem->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
        }

        // Clear cart and redirect
        clearCart();
        $_SESSION['thank_you'] = "Thank you for your purchase! Your order will be delivered by " . date('F j, Y', strtotime('+14 days')) . ".";
        header("Location: checkout.php");
        exit();
    }

    // ADMIN: MANAGE PRODUCTS
    elseif (isset($_POST['add_product']) || isset($_POST['update_product']) || isset($_POST['delete_product'])) {
        $pdo = connectDB();

        // ADD PRODUCT
        if (isset($_POST['add_product'])) {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $imagePath = uploadProductImage();

            $stmt = $pdo->prepare("INSERT INTO products (name, price, category, image_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name , $price, $category, $imagePath]);
        }

        // UPDATE PRODUCT
        elseif (isset($_POST['update_product'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $imagePath = uploadProductImage();

            if ($imagePath) {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, category = ?, image_url = ? WHERE id = ?");
                $stmt->execute([$name, $price, $category, $imagePath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, category = ? WHERE id = ?");
                $stmt->execute([$name, $price, $category, $id]);
            }
        }

        // DELETE PRODUCT
        elseif (isset($_POST['delete_product'])) {
            $id = $_POST['delete_id'];
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
        }

        header("Location: admindashboardproducts.php");
        exit();
    }

    // ADMIN: MANAGE USERS
    elseif (isset($_POST['add_user']) || isset($_POST['update_user']) || isset($_POST['delete_user'])) {
        $pdo = connectDB();

        // ADD USER
        if (isset($_POST['add_user'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $membership = $_POST['membership'];

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, membership) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username , $email, $password, $membership]);
        }

        // UPDATE USER
        elseif (isset($_POST['update_user'])) {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $membership = $_POST['membership'];

            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, membership = ? WHERE id = ?");
            $stmt->execute([$username, $email, $password, $membership, $id]);
        }

        // DELETE USER
        elseif (isset($_POST['delete_user'])) {
            $id = $_POST['delete_id'];
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
        }

        header("Location: admindashboardusers.php");
        exit();
    }
    // Redirect to login
    else {
        header("Location: login.php");
        exit();
    }
// ERROR HANDLING
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: login.php");
    exit();
}