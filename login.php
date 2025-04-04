<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT user_id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Create session
            $_SESSION['user_id'] = $user['user_id'];
            
            // Generate session token
            $session_token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("INSERT INTO sessions 
                (user_id, session_token, expiry_time)
                VALUES (?, ?, ?)");
            $stmt->execute([$user['user_id'], $session_token, $expiry]);
            
            setcookie('session_token', $session_token, time() + 3600, '/', '', true, true);
            
            header("Location: index.php");
            exit();
        } else {
            die("Invalid email or password");
        }
        
    } catch(PDOException $e) {
        die("Login failed: " . $e->getMessage());
    }
}
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

    <!-- Login Section -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-lg shadow-xl">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Welcome Back!
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sign in to access your account
                </p>
            </div>
            <form action="login.php" method="POST" class="mt-8 space-y-6" id="login-form">
                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Email -->
                    <div>
                        <label for="email" class="sr-only">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-red-500 
                            focus:border-red-500 focus:z-10 sm:text-sm"
                            placeholder="Email Address">
                    </div>
            
                    <!-- Password -->
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required minlength="8"
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 
                            placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-red-500 
                            focus:border-red-500 focus:z-10 sm:text-sm"
                            placeholder="Password">
                    </div>
                </div>
            
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember_me" type="checkbox"
                            class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="forgot-password.php" class="font-medium text-red-500 hover:text-red-600">
                            Forgot password?
                        </a>
                    </div>
                </div>
            
                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium 
                        rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 
                        focus:ring-red-500 transition-colors">
                        Sign In
                    </button>
                </div>
            
                <!-- Sign Up Link -->
                <div class="text-center text-sm">
                    <span class="text-gray-600">Don't have an account? </span>
                    <a href="signup.html" class="font-medium text-red-500 hover:text-red-600">
                        Sign up here
                    </a>
                </div>
            </form>
            
          
            
        </div>
    </main>

    <script>
        document.getElementById("login-form").addEventListener("submit", function (e) {
            const password = document.getElementById("password").value;
            if (password.length < 8) {
                e.preventDefault();
                alert("Password must be at least 8 characters long!");
            }
        });
    </script>

    <!-- Footer (same as other pages) -->
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