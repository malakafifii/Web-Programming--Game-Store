<?php include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<section class="page-hero">
			<p class="eyebrow">Products</p>
			<h1>Browse the full catalog.</h1>
			<p>This page reuses the same product card system as the homepage so the store stays visually consistent.</p>
		</section>

		<section class="section-block" id="featured">
			<div class="product-grid">
				<?php
				$catalog = [
					['Digital Release', '$59.99', 'Available now'],
					['Physical Collector Edition', '$89.99', 'Limited stock'],
					['Pro Headset', '$79.99', 'Clear sound'],
					['Display Figure', '$29.99', 'Collector item'],
					['Kids Toy Pack', '$24.99', 'Great gift set'],
					['Controller Stand', '$19.99', 'Desk accessory'],
				];

				foreach ($catalog as $item): ?>
					<article class="product-card">
						<div class="product-art"></div>
						<div class="product-copy">
							<h3><?php echo $item[0]; ?></h3>
							<p><?php echo $item[2]; ?></p>
							<div class="product-meta">
								<span class="price"><?php echo $item[1]; ?></span>
								<a href="#" class="btn btn-small">Add to Cart</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>
