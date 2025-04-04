<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giftify Home</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.FontAwesomeConfig = {
            autoReplaceSvg: 'nest',
        };
    </script>
    <script src="img/min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        * {
            font-family: sans-serif;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        .highlighted-section {
            outline: 2px solid #3F20FB;
            background-color: rgba(63, 32, 251, 0.1);
        }

        .edit-button {
            position: absolute;
            z-index: 1000;
        }

        :root {
            /* Light theme variables */
            --color-base: #ffffff;
            --color-base-content: #1f2937;
            --color-primary: #3b82f6;
            --color-secondary: #8b5cf6;
            --color-accent: #f472b6;
            --color-neutral: #6b7280;
            --color-info: #3b82f6;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-error: #ef4444;
        }

        .dark {
            /* Dark theme variables */
            --color-base: #1f2937;
            --color-base-content: #f9fafb;
            --color-primary: #60a5fa;
            --color-secondary: #a78bfa;
            --color-accent: #f472b6;
            --color-info: #60a5fa;
            --color-success: #34d399;
            --color-warning: #fbbf24;
            --color-error: #f87171;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        base: {
                            DEFAULT: 'var(--color-base)',
                            content: 'var(--color-base-content)'
                        },
                        primary: {
                            DEFAULT: 'var(--color-primary)'
                        },
                        secondary: {
                            DEFAULT: 'var(--color-secondary)'
                        },
                        accent: {
                            DEFAULT: 'var(--color-accent)'
                        },
                        info: {
                            DEFAULT: 'var(--color-info)'
                        },
                        success: {
                            DEFAULT: 'var(--color-success)'
                        },
                        warning: {
                            DEFAULT: 'var(--color-warning)'
                        },
                        error: {
                            DEFAULT: 'var(--color-error)'
                        }
                    }
                }
            }
        };
    </script>
</head>

<body class="h-full text-base-content">

     <!-- Header -->
     <header class="flex justify-between items-center px-10 py-4 shadow-md bg-white">
        <div class="text-2xl font-bold text-red-500 flex items-center">
            <i class="fa fa-gift mr-2"></i> GiftShop
        </div>
        <nav>
            <ul class="flex space-x-6 text-gray-700">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Sign Up</a></li>
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
  
  <script>
      function searchFunction() {
          let query = prompt("Enter your search query:");
          if (query) {
              window.location.href = `https://www.google.com/search?q=${encodeURIComponent(query)}`;
          }
      }
  </script>

    <section id="hero" class="bg-gradient-to-r from-rose-600 to-rose-500 h-[400px]">
        <div class="container mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Discover Perfect Gifts</h1>
            <p class="text-lg text-white/90 mb-8">Find unique and thoughtful gifts for every special occasion</p>
            <div class="w-full max-w-2xl relative">
                <input type="text" placeholder="Search for gifts..." class="w-full px-6 py-4 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-rose-400 pl-12">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </section>
    <section id="filters" class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap gap-4 items-center justify-between">
            <div class="flex gap-4">
                <button class="px-6 py-2 bg-rose-600 text-white rounded-full">All Items</button>
                <button class="px-6 py-2 bg-white text-gray-600 rounded-full hover:bg-gray-100">Personalized</button>
                <button class="px-6 py-2 bg-white text-gray-600 rounded-full hover:bg-gray-100">Luxury</button>
                <button class="px-6 py-2 bg-white text-gray-600 rounded-full hover:bg-gray-100">Jewelry</button>
                <button class="px-6 py-2 bg-white text-gray-600 rounded-full hover:bg-gray-100">Home Decor</button>
            </div>
            <select class="px-4 py-2 border rounded-lg bg-white">
                <option>Sort by: Featured</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest First</option>
            </select>
        </div>
    </section>
    <section id="products" class="container mx-auto px-4 py-8">
        <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Products will be dynamically loaded here -->
        </div>
    </section>

    <script>
       fetch("get-products.php")
.then(response => {
    if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
    return response.json();
})
.then(data => {
    if (!data.success) throw new Error(data.message);
    
    const productContainer = document.getElementById("product-list");
    productContainer.innerHTML = data.data.map(product => `
        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <img class="w-full h-64 object-cover" 
                 src="admin/product_images/${product.image}" 
                 alt="${product.name}"
                 onerror="this.src='admin/product_images/default-product.jpg'">
            <div class="p-4">
                <span class="text-sm text-rose-600">${product.category}</span>
                <h3 class="text-lg font-semibold mt-1">${product.name}</h3>
                <p class="text-gray-500 text-sm mt-1 line-clamp-2">${product.description}</p>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-xl font-bold">$${product.price}</span>
                    <button class="bg-rose-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 
                                hover:bg-rose-700 transition-colors add-to-cart"
                            data-product-id="${product.product_id}">
                        <i class="fa-solid fa-cart-plus"></i>
                        Add
                    </button>
                </div>
            </div>
        </div>
    `).join('');
})
.catch(error => {
    console.error('Error:', error);
    productContainer.innerHTML = `
        <div class="col-span-full text-center py-8">
            <div class="text-red-500 text-lg">${error.message}</div>
            <p class="text-gray-500 mt-2">Please try again later</p>
        </div>
    `;
});
    </script>

<script>    
 // Add to Cart functionality
document.addEventListener("click", function(e) {
    if (e.target.closest('.add-to-cart')) {
        const productId = e.target.closest('.add-to-cart').dataset.productId;
        addToCart(productId);
    }
});

async function addToCart(productId) {
    try {
        const response = await fetch("add-to-cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ productId })
        });

        // First check if response is JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            throw new Error(`Invalid response: ${text.substring(0, 100)}`);
        }

        const result = await response.json();
        
        if (!result.success) throw new Error(result.message);
        
        alert('Product added to cart!');
        // Optional: Update cart counter
        // document.querySelector('.cart-count').textContent = result.cartCount;

    } catch (error) {
        console.error('Cart Error:', error);
        alert('Failed to add to cart: ' + error.message);
    }
}

async function updateCartItem(productId, quantity) {
    try {
        const response = await fetch("update-cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                productId: productId,
                quantity: quantity
            })
        });

        const result = await response.json();
        if (!result.success) throw new Error(result.message);
        
        // Update UI elements
        document.querySelectorAll('.cart-total').forEach(el => {
            el.textContent = `$${result.cartTotal}`;
        });
        
        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = result.cartCount;
        });

        // Remove item row if quantity is 0
        if (quantity === 0) {
            document.querySelector(`[data-product-id="${productId}"]`).remove();
        }

    } catch (error) {
        console.error('Update Error:', error);
        alert('Failed to update cart: ' + error.message);
    }
}


</script>
   <!-- Footer -->
   <footer class="bg-gray-900 text-white py-10 px-10">
    <div class="grid grid-cols-4 gap-6">
        <div>
            <h3 class="text-lg font-bold">GiftShop</h3>
            <p>Making every moment special with thoughtfully curated gifts.</p>
        </div>
        <div>
            <h3 class="text-lg font-bold">Quick Links</h3>
            <ul>
                <li>About Us</li>
                <li>Gift Finder</li>
                <li>Corporate Gifts</li>
                <li>Contact Us</li>
            </ul>
        </div>
        <div>
            <h3 class="text-lg font-bold">Customer Service</h3>
            <ul>
                <li>Shipping Policy</li>
                <li>Returns & Exchanges</li>
                <li>FAQ</li>
                <li>Track Order</li>
            </ul>
        </div>
        <div>
            <h3 class="text-lg font-bold">Newsletter</h3>
            <p>Subscribe for updates, deals, and more.</p>
            <div class="mt-2 flex">
                <input type="email" placeholder="Enter your email" class="p-2 w-full rounded-l-md">
                <button class="bg-red-500 p-2 rounded-r-md"><i class="fa fa-paper-plane text-white"></i></button>
            </div>
        </div>
    </div>
    <p class="text-center mt-6">© 2025 GiftShop. All rights reserved.</p>
</footer>
</body>
</html>
