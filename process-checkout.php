<?php
session_start();
require_once 'config.php';

if (empty($_SESSION['cart']) || !isset($_SESSION['customer_id'])) {
    header('Location: cart.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Create order
    $invoiceNo = mt_rand(100000, 999999);
    $total = calculateTotal(); // Implement your total calculation function

    // Insert into customer_orders
    $orderStmt = $pdo->prepare("
        INSERT INTO customer_orders 
        (customer_id, due_amount, invoice_no, qty, order_status)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    
    $totalQty = array_sum(array_column($_SESSION['cart'], 'quantity'));
    $orderStmt->execute([
        $_SESSION['customer_id'],
        $total,
        $invoiceNo,
        $totalQty
    ]);
    $orderId = $pdo->lastInsertId();

    // Insert into pending_orders
    foreach ($_SESSION['cart'] as $productId => $item) {
        $pendingStmt = $pdo->prepare("
            INSERT INTO pending_orders 
            (order_id, customer_id, invoice_no, product_id, qty, order_status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $pendingStmt->execute([
            $orderId,
            $_SESSION['customer_id'],
            $invoiceNo,
            $productId,
            $item['quantity']
        ]);
    }

    // Record payment
    $paymentStmt = $pdo->prepare("
        INSERT INTO payments 
        (invoice_no, amount, payment_mode, ref_no, code, payment_date)
        VALUES (?, ?, 'credit_card', ?, 100, NOW())
    ");
    $refNo = mt_rand(100000000, 999999999);
    $paymentStmt->execute([
        $invoiceNo,
        $total,
        $refNo
    ]);

    $pdo->commit();
    
    // Clear cart and redirect
    unset($_SESSION['cart']);
    header('Location: order-confirmation.php?invoice=' . $invoiceNo);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    // Handle error
    die("Error processing order: " . $e->getMessage());
}

function calculateTotal() {
    // Implement your total calculation logic
    return $_POST['total']; // Should be calculated securely
}