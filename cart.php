<?php
session_start();
require_once 'config.php';

// Initialize cart and calculate totals
$subtotal = 0;
$cartItems = [];
$taxRate = 0.10; // 10% tax
$shipping = 0;    // Free shipping

// Calculate cart totals and get product details
foreach ($_SESSION['cart'] ?? [] as $productId => $item) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if ($product) {
        $itemTotal = $product['product_price'] * $item['quantity'];
        $subtotal += $itemTotal;
        
        $cartItems[] = [
            'id' => $productId,
            'product' => $product,
            'quantity' => $item['quantity'],
            'total' => $itemTotal
        ];
    }
}

$tax = $subtotal * $taxRate;
$total = $subtotal + $tax + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - GiftShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        * { font-family: sans-serif; }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="h-full text-base-content">
     <!-- Header -->
     <header class="flex justify-between items-center px-10 py-4 shadow-md bg-white">
        <div class="text-2xl font-bold text-red-500 flex items-center">
            <i class="fa fa-gift mr-2"></i> GiftShop
        </div>
        <nav>
            <ul class="flex space-x-6 text-gray-700">
                <li><a href="index.html">Home</a></li>
                <li><a href="products.html">Products</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="login.html">Login</a></li>
                <li><a href="signup.html">Sign Up</a></li>
            </ul>
        </nav>
        <div class="flex space-x-4">
          <!-- Search Icon -->
          <i class="fa fa-search text-gray-700 cursor-pointer" onclick="searchFunction()"></i>
          <!-- Cart Button -->
          <a href="cart.php" class="relative">
            <i class="fa fa-shopping-cart text-gray-700"></i>
            <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                <?= array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')) ?>
            </span>
        </a>
      </div>
  </header>

    <!-- Cart Content -->
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>
        
        <!-- Cart Items -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <?php foreach ($cartItems as $cartItem): 
                $product = $cartItem['product'];
                $productId = $cartItem['id'];
            ?>
            <div class="cart-item flex flex-col md:flex-row items-center justify-between border-b pb-4 mb-4"
                 data-product-id="<?= $productId ?>">
                <div class="flex items-center w-full md:w-2/3 mb-4 md:mb-0">
                    <img src="admin/product_images/<?= htmlspecialchars($product['product_img1']) ?>" 
                         alt="<?= htmlspecialchars($product['product_title']) ?>" 
                         class="w-20 h-20 object-cover rounded-lg"
                         onerror="this.src='admin/product_images/default-product.jpg'">
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold"><?= htmlspecialchars($product['product_title']) ?></h3>
                        <p class="text-gray-600">$<?= number_format($product['product_price'], 2) ?></p>
                        <button class="remove-item text-red-500 text-sm mt-2 hover:text-red-700">
                            <i class="fa fa-trash mr-1"></i>Remove
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between w-full md:w-1/3">
                    <div class="flex items-center">
                        <button class="quantity-decrease bg-gray-200 px-3 py-1 rounded-l">-</button>
                        <input type="number" 
                               class="quantity-input w-12 text-center border-y bg-white py-1"
                               value="<?= $cartItem['quantity'] ?>"
                               min="1">
                        <button class="quantity-increase bg-gray-200 px-3 py-1 rounded-r">+</button>
                    </div>
                    <span class="item-total text-lg font-bold ml-4">
                        $<?= number_format($cartItem['total'], 2) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($cartItems)): ?>
                <p class="text-gray-500 text-center py-4">Your cart is empty</p>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
       <!-- In cart.php -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
    <div class="space-y-4">
        <div class="flex justify-between">
            <span>Subtotal (<span id="summary-item-count"><?= count($cartItems) ?></span> items)</span>
            <span id="summary-subtotal">$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="flex justify-between">
            <span>Shipping</span>
            <span class="text-green-500">Free</span>
        </div>
        <div class="flex justify-between">
            <span>Taxes (<?= ($taxRate * 100) ?>%)</span>
            <span id="summary-tax">$<?= number_format($tax, 2) ?></span>
        </div>
        <div class="flex justify-between border-t pt-4">
            <span class="font-bold">Total</span>
            <span class="font-bold" id="summary-total">$<?= number_format($total, 2) ?></span>
        </div>
    </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-4">
                <a href="products.php" class="px-6 py-3 border border-red-500 text-red-500 rounded-md hover:bg-red-50 text-center">
                    Continue Shopping
                </a>
                <a href="checkout.php" class="px-6 py-3 bg-red-500 text-white rounded-md hover:bg-red-600 text-center">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-10 px-10 mt-12">
        <!-- ... keep existing footer ... -->
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const formatCurrency = (value) => `$${value?.toFixed(2) || '0.00'}`;

    const updateOrderSummary = (result) => {
        document.getElementById('summary-item-count').textContent = result.itemCount;
        document.getElementById('summary-subtotal').textContent = formatCurrency(result.cartSubtotal);
        document.getElementById('summary-tax').textContent = formatCurrency(result.cartTax);
        document.getElementById('summary-total').textContent = formatCurrency(result.cartTotal);
    };

    const updateQuantity = async (e) => {
        const input = e.target;
        const item = input.closest('.cart-item');
        const productId = item.dataset.productId;
        const quantity = parseInt(input.value) || 0;

        try {
            const response = await fetch('update-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ productId, quantity })
            });

            const result = await response.json();
            if (!result.success) throw new Error(result.message);

            // Update item total
            item.querySelector('.item-total').textContent = formatCurrency(result.itemTotal);
            
            // Update cart counter
            document.querySelectorAll('.cart-count').forEach(el => 
                el.textContent = result.cartCount);
            
            // Update order summary
            updateOrderSummary(result);

            if (quantity === 0) item.remove();

        } catch (error) {
            console.error('Update error:', error);
            input.value = input.oldValue;
            alert(error.message);
        }
    };

    const adjustQuantity = (e) => {
            const button = e.target;
            const input = button.parentElement.querySelector('.quantity-input');
            let value = parseInt(input.value);

            if (button.classList.contains('quantity-increase')) {
                value++;
            } else if (button.classList.contains('quantity-decrease') && value > 1) {
                value--;
            }

            input.value = value;
            input.dispatchEvent(new Event('change'));
        };

        const removeItem = async (e) => {
            const button = e.target.closest('.remove-item');
            const item = button.closest('.cart-item');
            const productId = item.dataset.productId;

            try {
                const response = await fetch('remove-from-cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ productId })
                });

                const result = await response.json();
                if (!result.success) throw new Error(result.message);

                item.remove();
                document.querySelectorAll('.cart-count').forEach(el => 
                    el.textContent = result.cartCount);
                document.querySelectorAll('.cart-total').forEach(el => 
                    el.textContent = `$${result.cartTotal.toFixed(2)}`);

            } catch (error) {
                console.error('Remove error:', error);
                alert(error.message);
            }
        };

        // Event Listeners
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', updateQuantity);
        });

        document.querySelectorAll('.quantity-increase, .quantity-decrease').forEach(button => {
            button.addEventListener('click', adjustQuantity);
        });

        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', removeItem);
        });
    });
    </script>
</body>
</html>