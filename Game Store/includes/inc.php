<header class="topbar">
    <form class="search-form" action="search.php" method="GET">
        <input type="text" name="q" placeholder="Search games, consoles, accessories..." class="search-input">
    </form>

    <div class="topbar-icons">
        <?php if(isset($_SESSION['user'])): ?>
            <span class="welcome-text">Hello, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>

        <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
    </div>
</header>