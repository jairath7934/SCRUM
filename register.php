<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $required = ['full_name', 'email', 'phone', 'password', 'confirm_password', 
                'street_address', 'city', 'state', 'postal_code', 'country'];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            die("Please fill all required fields");
        }
    }

    if ($_POST['password'] !== $_POST['confirm_password']) {
        die("Passwords do not match");
    }

    // Sanitize inputs
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $street_address = htmlspecialchars($_POST['street_address']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $postal_code = htmlspecialchars($_POST['postal_code']);
    $country = htmlspecialchars($_POST['country']);

    try {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            die("Email already registered");
        }

        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users 
            (full_name, email, phone, password_hash, street_address, city, state, postal_code, country)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $full_name,
            $email,
            $phone,
            $password,
            $street_address,
            $city,
            $state,
            $postal_code,
            $country
        ]);

        header("Location: login.php?registration=success");
        exit();

    } catch(PDOException $e) {
        die("Registration failed: " . $e->getMessage());
    }
}
?>