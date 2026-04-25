<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Toy Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="topbar">

    <!-- Search -->
    <form action="search.php" method="GET">
        <input type="text" name="q" placeholder="Search..." class="search">
    </form>

    <!-- Right side -->
    <div class="icons">

        <?php if(isset($_SESSION['user'])): ?>
            <span>Hello, <?php echo $_SESSION['user']; ?></span>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>

        <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>

    </div>
</div>