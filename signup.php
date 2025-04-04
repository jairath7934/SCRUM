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

    <!-- Signup Form -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-lg shadow-xl">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Create Your Account
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Start your gift-giving journey with us
                </p>
            </div>
            
            <form action="register.php" method="POST" class="mt-8 space-y-6" id="registration-form">
                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Full Name -->
                    <div>
                        <label for="full-name" class="sr-only">Full Name</label>
                        <input id="full-name" name="full_name" type="text" required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-red-500 
                            focus:border-red-500 focus:z-10 sm:text-sm" placeholder="Full Name">
                    </div>
            
                    <!-- Email -->
                    <div>
                        <label for="email" class="sr-only">Email Address</label>
                        <input id="email" name="email" type="email" required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                            focus:z-10 sm:text-sm" placeholder="Email Address">
                    </div>
            
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="sr-only">Phone Number</label>
                        <input id="phone" name="phone" type="tel" required pattern="^\d{10,15}$"
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                            focus:z-10 sm:text-sm" placeholder="Phone Number">
                    </div>
            
                    <!-- Street Address -->
                    <div>
                        <label for="street-address" class="sr-only">Street Address</label>
                        <input id="street-address" name="street_address" type="text" required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                            focus:z-10 sm:text-sm" placeholder="Street Address">
                    </div>
            
                    <div class="grid grid-cols-2 gap-0">
                        <div class="col-span-1">
                            <label for="city" class="sr-only">City</label>
                            <input id="city" name="city" type="text" required
                                class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                                placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                                focus:z-10 sm:text-sm" placeholder="City">
                        </div>
                        <div class="col-span-1">
                            <label for="state" class="sr-only">State/Province</label>
                            <input id="state" name="state" type="text" required
                                class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                                placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                                focus:z-10 sm:text-sm" placeholder="State/Province">
                        </div>
                    </div>
            
                    <div class="grid grid-cols-2 gap-0">
                        <div class="col-span-1">
                            <label for="postal-code" class="sr-only">Postal Code</label>
                            <input id="postal-code" name="postal_code" type="text" required pattern="^[A-Za-z0-9]{3,10}$"
                                class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                                placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                                focus:z-10 sm:text-sm" placeholder="Postal Code">
                        </div>
                        <div class="col-span-1">
                            <label for="country" class="sr-only">Country</label>
                            <select id="country" name="country" required
                                class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                                placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                                focus:z-10 sm:text-sm pr-10">
                                <option value="" disabled selected>Country</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="GB">United Kingdom</option>
                                <option value="AU">Australia</option>
                            </select>
                        </div>
                    </div>
            
                    <!-- Password -->
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required minlength="8"
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 
                            focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                    <div>
                        <label for="confirm-password" class="sr-only">Confirm Password</label>
                        <input id="confirm-password" name="confirm_password" type="password" required minlength="8"
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-red-500 
                            focus:border-red-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                    </div>
                </div>
            
                <!-- Terms -->
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300 rounded cursor-pointer">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the <a href="#" class="text-red-500 hover:text-red-600">Terms & Conditions</a>
                    </label>
                </div>
            
                <!-- Submit -->
                <button type="submit"
                    class="w-full py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white 
                    bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 
                    focus:ring-red-500 transition-colors">
                    Create Account
                </button>
            
                <!-- Login Link -->
                <div class="text-center text-sm">
                    <span class="text-gray-600">Already have an account? </span>
                    <a href="login.html" class="font-medium text-red-500 hover:text-red-600">
                        Log in here
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById("registration-form").addEventListener("submit", function (e) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
    
            if (password !== confirmPassword) {
                e.preventDefault();
                alert("Passwords do not match!");
            }
        });
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
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-red-500">About Us</a></li>
                    <li><a href="#" class="hover:text-red-500">Gift Finder</a></li>
                    <li><a href="#" class="hover:text-red-500">Corporate Gifts</a></li>
                    <li><a href="#" class="hover:text-red-500">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold">Customer Service</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-red-500">Shipping Policy</a></li>
                    <li><a href="#" class="hover:text-red-500">Returns & Exchanges</a></li>
                    <li><a href="#" class="hover:text-red-500">FAQ</a></li>
                    <li><a href="#" class="hover:text-red-500">Track Order</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold">Newsletter</h3>
                <p>Subscribe for updates, deals, and more.</p>
                <div class="mt-2 flex">
                    <input type="email" placeholder="Enter your email" 
                        class="p-2 w-full rounded-l-md text-gray-900">
                    <button class="bg-red-500 p-2 rounded-r-md hover:bg-red-600 transition-colors">
                        <i class="fa fa-paper-plane text-white"></i>
                    </button>
                </div>
            </div>
        </div>
        <p class="text-center mt-6">&copy; 2025 GiftShop. All rights reserved.</p>
    </footer>
</body>
</html>