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
    
    elseif (isset($_POST['add_user']) || isset($_POST['update_user']) || isset($_POST['delete_user'])) {
        $pdo = connectDB();
    
        // ADD User
        if (isset($_POST['add_user'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $membership = $_POST['membership'];
    
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, membership) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username , $email, $password, $membership]);
        }
    
        // UPDATE User
        elseif (isset($_POST['update_user'])) {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $membership = $_POST['membership'];

            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, membership = ? WHERE id = ?");
            $stmt->execute([$username, $email, $password, $membership, $id]);
        }
    
        // DELETE User
        elseif (isset($_POST['delete_user'])) {
            $id = $_POST['delete_id'];
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
        }
    
        header("Location: admindashboardusers.php");
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