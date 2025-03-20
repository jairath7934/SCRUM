<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get and validate input
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = filter_var($input['productId'] ?? null, FILTER_VALIDATE_INT);

    if (!$productId || $productId <= 0) {
        throw new Exception('Invalid product ID');
    }

    // Remove from cart
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

 // After removing item
$subtotal = 0;

foreach ($_SESSION['cart'] as $id => $item) {
    $stmt = $pdo->prepare("SELECT product_price FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    $subtotal += $product['product_price'] * $item['quantity'];
}

$tax = $subtotal * 0.10;
$total = $subtotal + $tax;

echo json_encode([
    'success' => true,
    'cartSubtotal' => $subtotal,
    'cartTax' => $tax,
    'cartTotal' => $total,
    'itemCount' => count($_SESSION['cart']),
    'cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity'))
]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}