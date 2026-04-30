<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toy Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        span{
            color:red;
            font-family: Bahnschrift, serif;

        }
    </style>
</head>
<body>

<div class="topbar">

    <!-- ROW 1 -->
    <div class="row-1">
        <?php if(isset($_SESSION['user'])): ?>
            Hello,<span><?php echo  ucfirst($_SESSION["user"]); ?></span>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

    <!-- ROW 2 -->
    <div class="row-2">

        <form action="search.php" method="GET" class="search-box">
            <input type="text" name="q" placeholder="Search..." class="search">
        </form>

        <div class="cart">
            <a href="cart.php">
                Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
            </a>
        </div>

    </div>

</div>
</body>
</html>