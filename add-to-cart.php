<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Verify request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = $input['productId'] ?? null;

    // Validate product ID
    if (!$productId || !is_numeric($productId)) {
        throw new Exception('Invalid product ID');
    }

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add to cart logic
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        $_SESSION['cart'][$productId] = [
            'quantity' => 1
        ];
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart',
        'cartCount' => count($_SESSION['cart'])
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>