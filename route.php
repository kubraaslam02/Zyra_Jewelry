<?php
session_start();
include "functions.php";

try {
    if (isset($_POST['login'])) {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (loginUser($email, $password)) {
            header("Location: home.php");
            exit();
        } else {
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

        $referrer = $_SERVER['HTTP_REFERER'] ?? 'home.php'; // Redirect back to where the user came from
        header("Location: $referrer");
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