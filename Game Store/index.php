<?php include 'includes/header.php'; ?>

<div class="layout">
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-pane">
        <?php include 'includes/inc.php'; ?>

        <section class="hero-card">
            <div class="hero-copy">
                <p class="eyebrow">New season drops</p>
                <h1>Play More. <span>Pay Less.</span></h1>
                <p>Discover the latest games, accessories, collectibles, and toys with a layout that keeps browsing fast and clear.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#categories">Shop Now</a>
                    <a class="btn btn-secondary" href="products.php">Browse Games</a>
                </div>
            </div>

            <div class="hero-visual">
                <img src="images/thumbnail.png" alt="Gaming gear including Spider-Man 2, Call of Duty, controller, and headphones" style="width: 100%; height: auto; object-fit: contain;">
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
                <a class="text-link" href="products.php">View all</a>
            </div>

            <div class="product-grid">
                <?php
                $featuredProducts = getFeaturedProducts(4);
                if (!empty($featuredProducts)):
                    foreach ($featuredProducts as $product): ?>
                        <article class="product-card">
                            <div class="product-art">
                                <img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'images/thumbnail.png'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                            <div class="product-copy">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p><?php echo htmlspecialchars(mb_substr($product['description'], 0, 120)); ?></p>
                                <div class="product-meta">
                                    <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                                    <a href="ptoduct.php?id=<?php echo urlencode($product['slug']); ?>" class="btn btn-small">View</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach;
                else: ?>
                    <div class="panel full-col">
                        <h3>No items available</h3>
                        <p>The catalog is empty right now. Browse products later after an admin adds listings.</p>
                    </div>
                <?php endif; ?>
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