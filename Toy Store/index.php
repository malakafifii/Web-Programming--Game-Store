<?php include 'includes/header.php'; ?>

<div class="layout">
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-pane">
        <header class="topbar">
            <form class="search-form" action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search games, consoles, accessories..." class="search-input">
            </form>

            <div class="topbar-icons">
                <?php if(isset($_SESSION['user'])): ?>
                    <span class="welcome-text">Hello, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>

                <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
            </div>
        </header>

        <section class="hero-card">
            <div class="hero-copy">
                <p class="eyebrow">New season drops</p>
                <h1>Play More. <span>Pay Less.</span></h1>
                <p>Discover the latest games, accessories, collectibles, and toys with a layout that keeps browsing fast and clear.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#games">Shop Now</a>
                    <a class="btn btn-secondary" href="#featured">Browse Games</a>
                </div>
            </div>

            <div class="hero-visual" aria-hidden="true">
                <div class="hero-ribbon"></div>
                <div class="hero-card-image hero-card-image-large"></div>
                <div class="hero-card-image hero-card-image-small"></div>
                <div class="hero-controller"></div>
            </div>
        </section>

        <section class="section-block" id="categories">
            <div class="section-heading">
                <p>Shop by category</p>
                <h2>Everything the store needs, grouped cleanly.</h2>
            </div>

            <div class="category-grid">
                <?php
                $categories = [
                    ['Games', 'Digital + physical releases', 'games-category'],
                    ['Accessories', 'Controllers, headsets, charging gear', 'accessories'],
                    ['Collectibles', 'Figures, Funko Pops, display pieces', 'collectibles'],
                    ['Toys', 'Action figures, plush, and playsets', 'toys'],
                ];

                foreach ($categories as $category): ?>
                    <article class="category-card" id="<?php echo $category[2]; ?>">
                        <span class="category-icon"><?php echo strtoupper(substr($category[0], 0, 1)); ?></span>
                        <h3><?php echo $category[0]; ?></h3>
                        <p><?php echo $category[1]; ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section-block" id="featured">
            <div class="section-heading split">
                <div>
                    <p>Featured products</p>
                    <h2>Best sellers with a strong retail feel.</h2>
                </div>
                <a class="text-link" href="#">View all</a>
            </div>

            <div class="product-grid">
                <?php
                $featuredProducts = [
                    ['Wireless Pro Controller', '$69.99', 'Best for precision play'],
                    ['Spider Hero Collector Set', '$39.99', 'Limited edition display item'],
                    ['Next-Gen RPG Bundle', '$59.99', 'Digital + physical edition'],
                    ['RGB Gaming Headset', '$49.99', 'Comfortable for long sessions'],
                ];

                foreach ($featuredProducts as $product): ?>
                    <article class="product-card">
                        <div class="product-art"></div>
                        <div class="product-copy">
                            <h3><?php echo $product[0]; ?></h3>
                            <p><?php echo $product[2]; ?></p>
                            <div class="product-meta">
                                <span class="price"><?php echo $product[1]; ?></span>
                                <a href="#" class="btn btn-small">Add to Cart</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section-block alt-surface" id="trending">
            <div class="section-heading split">
                <div>
                    <p>Trending now</p>
                    <h2>Popular picks that move quickly.</h2>
                </div>
                <a class="text-link" href="#">See trends</a>
            </div>

            <div class="trend-grid">
                <article class="trend-card">
                    <span>Top seller</span>
                    <h3>Open-world action games</h3>
                    <p>Big maps, strong replay value, and collector-friendly editions.</p>
                </article>
                <article class="trend-card">
                    <span>Hot accessory</span>
                    <h3>Hall-effect controllers</h3>
                    <p>Responsive hardware designed for everyday play and longer life.</p>
                </article>
                <article class="trend-card">
                    <span>Fan favorite</span>
                    <h3>Display-ready figures</h3>
                    <p>Clean shelves, bold packaging, and easy gift ideas.</p>
                </article>
            </div>
        </section>

        <section class="section-block deals-band" id="deals">
            <div>
                <p>Deals and discounts</p>
                <h2>Limited-time offers on games, accessories, and toys.</h2>
            </div>
            <a class="btn btn-primary" href="#">Shop Deals</a>
        </section>
    </main>
</div>

<?php include 'includes/footer.php'; ?>