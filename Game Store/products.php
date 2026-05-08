<?php include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>

		<section class="page-hero">
			<p class="eyebrow">Products</p>
			<h1>Browse the full catalog.</h1>
			<p>This page reuses the same product card system as the homepage so the store stays visually consistent.</p>
		</section>

		<section class="section-block" id="featured">
			<div class="product-grid">
				<?php
				$catalog = getProducts();
				foreach ($catalog as $item): ?>
					<article class="product-card">
						<div class="product-art"></div>
						<div class="product-copy">
							<h3><?php echo htmlspecialchars($item['name']); ?></h3>
							<p><?php echo htmlspecialchars($item['short_description']); ?></p>
							<div class="product-meta">
								<span class="price">$<?php echo number_format($item['price'], 2); ?></span>
								<a href="ptoduct.php?id=<?php echo urlencode($item['slug']); ?>" class="btn btn-small">View</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>