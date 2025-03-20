<?php
require 'config.php';
session_start();

if (isset($_COOKIE['session_token'])) {
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM sessions 
                             WHERE session_token = ? AND expiry_time > NOW()");
        $stmt->execute([$_COOKIE['session_token']]);
        $session = $stmt->fetch();

        if ($session) {
            $_SESSION['user_id'] = $session['user_id'];
        } else {
            header("Location: login.php");
            exit();
        }
        
    } catch(PDOException $e) {
        die("Session verification failed: " . $e->getMessage());
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>