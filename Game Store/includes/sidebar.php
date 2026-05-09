<aside class="sidebar">
    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
    <div class="brand-lockup">
        <a class="brand" href="index.php">
            <span class="brand-mark">Game</span><span class="brand-accent">Store</span>
        </a>
        <p>Games, accessories, collectibles, and toys in one bold storefront.</p>
    </div>

    <nav class="sidebar-nav" aria-label="Primary">
        <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">Home</a>
        <a href="products.php" class="<?php echo $currentPage === 'products.php' ? 'active' : ''; ?>">Products</a>
        <?php if (isAdmin()): ?>
            <a href="admin.php" class="<?php echo $currentPage === 'admin.php' ? 'active' : ''; ?>">Add Products</a>
        <?php endif; ?>
        <a href="about.php" class="<?php echo $currentPage === 'about.php' ? 'active' : ''; ?>">About</a>
        <a href="contact.php" class="<?php echo $currentPage === 'contact.php' ? 'active' : ''; ?>">Contact</a>
        <a href="search.php" class="<?php echo $currentPage === 'search.php' ? 'active' : ''; ?>">Search</a>
        <a href="cart.php" class="<?php echo $currentPage === 'cart.php' ? 'active' : ''; ?>">Cart</a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-badge">All ages. All play styles.</div>
        <div class="sidebar-socials" aria-label="Social links">
            <img width="48" height="48" src="https://img.icons8.com/fluency/48/facebook.png" alt="facebook"/>
            <img width="48" height="48" src="https://img.icons8.com/fluency/48/instagram-new.png" alt="instagram-new"/>
            <img width="48" height="48" src="https://img.icons8.com/color/48/google-maps.png" alt="google-maps"/>
        </div>
    </div>
</aside>