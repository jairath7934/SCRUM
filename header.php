<?php
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>

<header class="flex justify-between items-center px-10 py-4 shadow-md bg-white">
   
    <div class="flex items-center space-x-4">
        <?php if($isLoggedIn): ?>
            <a href="logout.php" class="text-gray-700 hover:text-red-500 ml-2">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</header>