<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT user_id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Create session
            $_SESSION['user_id'] = $user['user_id'];
            
            // Generate session token
            $session_token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("INSERT INTO sessions 
                (user_id, session_token, expiry_time)
                VALUES (?, ?, ?)");
            $stmt->execute([$user['user_id'], $session_token, $expiry]);
            
            setcookie('session_token', $session_token, time() + 3600, '/', '', true, true);
            
            header("Location: dashboard.php");
            exit();
        } else {
            die("Invalid email or password");
        }
        
    } catch(PDOException $e) {
        die("Login failed: " . $e->getMessage());
    }
}
?>