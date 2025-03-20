<?php
session_start();
require_once 'config.php';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Calculate totals
$subtotal = 0;
$taxRate = 0.10;
$shipping = 0;

foreach ($_SESSION['cart'] as $productId => $item) {
    $stmt = $pdo->prepare("SELECT product_price FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    $subtotal += $product['product_price'] * $item['quantity'];
}

$tax = $subtotal * $taxRate;
$total = $subtotal + $tax + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - GiftShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <!-- Same header as other pages -->
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <form id="checkout-form" action="process-checkout.php" method="POST" class="space-y-6">
                    <!-- Shipping Address -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-4">Shipping Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">First Name</label>
                                <input type="text" name="first_name" required 
                                       class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Last Name</label>
                                <input type="text" name="last_name" required 
                                       class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Address</label>
                                <input type="text" name="address" required 
                                       class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">City</label>
                                <input type="text" name="city" required 
                                       class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">ZIP Code</label>
                                <input type="text" name="zip" required 
                                       class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="email" name="email" required 
                                       class="w-full px-4 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Phone</label>
                                <input type="tel" name="phone" required 
                                       class="w-full px-4 py-2 border rounded-md">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-4">Payment Details</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Card Number</label>
                                <div class="relative">
                                    <input type="text" name="card_number" required 
                                           pattern="\d{16}" placeholder="4242 4242 4242 4242"
                                           class="w-full px-4 py-2 border rounded-md">
                                    <i class="fa fa-credit-card absolute right-3 top-3 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Expiration</label>
                                    <input type="text" name="expiry" required 
                                           pattern="\d{2}/\d{2}" placeholder="MM/YY"
                                           class="w-full px-4 py-2 border rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">CVC</label>
                                    <input type="text" name="cvc" required 
                                           pattern="\d{3}" placeholder="123"
                                           class="w-full px-4 py-2 border rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="bg-white p-6 rounded-lg shadow-md h-fit sticky top-8">
                <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                <div class="space-y-4">
                    <?php foreach ($_SESSION['cart'] as $productId => $item): 
                        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
                        $stmt->execute([$productId]);
                        $product = $stmt->fetch();
                    ?>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-500"><?= $item['quantity'] ?>x</span>
                            <span><?= htmlspecialchars($product['product_title']) ?></span>
                        </div>
                        <span>$<?= number_format($product['product_price'] * $item['quantity'], 2) ?></span>
                    </div>
                    <?php endforeach; ?>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping:</span>
                            <span class="text-green-500">Free</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (10%):</span>
                            <span>$<?= number_format($tax, 2) ?></span>
                        </div>
                        <div class="flex justify-between font-bold border-t pt-4">
                            <span>Total:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                </div>

                <button type="submit" form="checkout-form" 
                        class="w-full mt-6 bg-red-500 text-white py-3 rounded-md hover:bg-red-600 transition-colors">
                    Complete Checkout
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-10 px-10 mt-12">
   
    </footer>
</body>
</html>