<?php
require 'header.php';
?>
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
  
  <script>
      function searchFunction() {
          let query = prompt("Enter your search query:");
          if (query) {
              window.location.href = `https://www.google.com/search?q=${encodeURIComponent(query)}`;
          }
      }
  </script>
    <main id="main" class="pt-20">
      <section id="hero" class="relative h-[600px] bg-gradient-to-r from-rose-50 to-rose-100">
          <div class="container mx-auto px-4 h-full flex items-center">
              <div class="w-full lg:w-1/2 pr-0 lg:pr-12">
                  <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 leading-tight">
                      Discover Perfect Gifts for Every Special Moment
                  </h2>
                  <p class="mt-6 text-lg text-gray-600">
                      Curated collection of thoughtful gifts for all occasions, delivered with love and care to make every celebration memorable.
                  </p>
                  <div class="mt-8 flex flex-wrap gap-4"> 
                      <a href="signup.html">
                          <button class="px-8 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-full font-medium">
                              Sign Up
                          </button>
                      </a>
                      <a href="login.html">
                          <button class="px-8 py-3 bg-white hover:bg-gray-50 text-rose-500 rounded-full font-medium border-2 border-rose-500">
                              Login
                          </button>
                      </a>
                  </div>
              </div>
              <div class="hidden lg:block w-1/2">
                  <img class="w-full h-[500px] object-cover rounded-lg" src="img/gift.jpg" alt="modern gift shop display with beautifully wrapped presents, soft lighting, minimalist aesthetic, professional product photography">
              </div>
          </div>
      </section>
  
        <section id="categories" class="py-20">
          <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">Shop by Occasion</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
              <div class="group relative rounded-xl overflow-hidden">
                <img class="w-full h-64 object-cover" src="img/birthday.png" alt="birthday celebration setup with gifts and decorations, warm lighting, lifestyle photography" >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h4 class="text-white text-xl font-bold">Birthdays</h4>
                  <p class="text-rose-200 mt-1">Starting at $25</p>
                </div>
              </div>
              <div class="group relative rounded-xl overflow-hidden">
                <img class="w-full h-64 object-cover" src="img/weddings.png" alt="elegant wedding gift display with white and silver theme, soft lighting" >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h4 class="text-white text-xl font-bold">Weddings</h4>
                  <p class="text-rose-200 mt-1">Starting at $50</p>
                </div>
              </div>
              <div class="group relative rounded-xl overflow-hidden">
                <img class="w-full h-64 object-cover" src="img/anniversary.png" alt="romantic anniversary gift setup with roses and champagne, warm lighting" >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h4 class="text-white text-xl font-bold">Anniversary</h4>
                  <p class="text-rose-200 mt-1">Starting at $40</p>
                </div>
              </div>
              <div class="group relative rounded-xl overflow-hidden">
                <img class="w-full h-64 object-cover" src="img/holidays.png" alt="festive holiday gifts with traditional decorations, cozy lighting" >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                  <h4 class="text-white text-xl font-bold">Holidays</h4>
                  <p class="text-rose-200 mt-1">Starting at $30</p>
                </div>
              </div>
            </div>
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
 
                
        <section id="personalization" class="py-20">
          <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-12">
              <div class="w-full lg:w-1/2">
                <h3 class="text-3xl font-bold text-gray-800">Make it Personal</h3>
                <p class="mt-4 text-gray-600 text-lg">Create a unique gift experience in three simple steps:</p>
                <div class="mt-8 space-y-6">
                  <div class="flex items-start">
                    <div class="bg-rose-100 rounded-full p-3">
                      <i class="fa-solid fa-gift text-rose-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                      <h4 class="font-semibold text-lg">Select Your Gift</h4>
                      <p class="text-gray-600 mt-1">Browse our curated collection of premium gifts</p>
                    </div>
                  </div>
                  <div class="flex items-start">
                    <div class="bg-rose-100 rounded-full p-3">
                      <i class="fa-solid fa-wand-magic-sparkles text-rose-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                      <h4 class="font-semibold text-lg">Personalize It</h4>
                      <p class="text-gray-600 mt-1">Add your special touch with custom messages or designs</p>
                    </div>
                  </div>
                  <div class="flex items-start">
                    <div class="bg-rose-100 rounded-full p-3">
                      <i class="fa-solid fa-truck-fast text-rose-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                      <h4 class="font-semibold text-lg">Quick Delivery</h4>
                      <p class="text-gray-600 mt-1">We'll handle the wrapping and delivery with care</p>
                    </div>
                  </div>
                </div>
                <button class="mt-8 px-8 py-3 bg-rose-500 text-white rounded-full hover:bg-rose-600">Start Creating</button>
              </div>
              <div class="w-full lg:w-1/2">
                <img class="w-full h-[400px] object-cover rounded-xl" src="img/process.jpg" alt="gift personalization process showing various customization options, modern and clean design" >
              </div>
            </div>
          </div>
        </section>
        <section id="testimonials" class="py-20 bg-gray-50">
          <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">What Our Customers Say</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex text-yellow-400 mb-4">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-gray-600">"The personalization options are amazing! My friend loved the custom gift I created. Will definitely order again!"</p>
                <div class="mt-6 flex items-center">
                  <img src="img/avatar-1.jpg" alt="Customer" class="w-12 h-12 rounded-full">
                  <div class="ml-4">
                    <h4 class="font-semibold">Sarah Johnson</h4>
                    <p class="text-sm text-gray-500">Verified Buyer</p>
                  </div>
                </div>
              </div>
              <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex text-yellow-400 mb-4">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-gray-600">"Exceptional quality and service! The gift arrived beautifully wrapped and right on time. Couldn't be happier!"</p>
                <div class="mt-6 flex items-center">
                  <img src="img/avatar-2.jpg" alt="Customer" class="w-12 h-12 rounded-full">
                  <div class="ml-4">
                    <h4 class="font-semibold">Michael Chen</h4>
                    <p class="text-sm text-gray-500">Verified Buyer</p>
                  </div>
                </div>
              </div>
              <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex text-yellow-400 mb-4">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-gray-600">"The gift finder tool made it so easy to choose the perfect present. The recipient was absolutely delighted!"</p>
                <div class="mt-6 flex items-center">
                  <img src="img/avatar-3.jpg" alt="Customer" class="w-12 h-12 rounded-full">
                  <div class="ml-4">
                    <h4 class="font-semibold">Emma Wilson</h4>
                    <p class="text-sm text-gray-500">Verified Buyer</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

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
