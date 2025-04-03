<?php
require 'config.php';

// Only start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_COOKIE['session_token'])) {
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM sessions 
                             WHERE session_token = ? AND expiry_time > NOW()");
        $stmt->execute([$_COOKIE['session_token']]);
        $session = $stmt->fetch();

        if ($session) {
            $_SESSION['user_id'] = $session['user_id'];
            
            // Optional: Fetch and store email if not already in session
            if (!isset($_SESSION['user_email'])) {
                $stmt = $pdo->prepare("SELECT email FROM users WHERE user_id = ?");
                $stmt->execute([$session['user_id']]);
                $user = $stmt->fetch();
                if ($user) {
                    $_SESSION['user_email'] = $user['email'];
                }
            }
        } else {
            // Clear invalid session
            setcookie('session_token', '', time() - 3600, '/');
            header("Location: login.php");
            exit();
        }
        
    } catch(PDOException $e) {
        error_log("Session verification failed: " . $e->getMessage());
        header("Location: login.php");
        exit();
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>