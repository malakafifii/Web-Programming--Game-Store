<?php include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>

		<?php
		$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
		$availableCategories = getCategories();
		$catalog = getProductsByCategory($selectedCategory);
		?>

		<section class="page-hero">
			<p class="eyebrow">Products</p>
			<h1>Browse the full catalog.</h1>
			<p>Shop by category to move from toys to video games, board games, accessories, and collectibles without leaving the page.</p>
		</section>

		<section class="section-block">
			<div class="badge-row">
				<a class="badge" href="products.php">All</a>
				<?php foreach ($availableCategories as $category): ?>
					<a class="badge<?php echo $selectedCategory === $category ? ' active' : ''; ?>" href="products.php?category=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="section-block" id="featured">
			<div class="product-grid">
				<?php if (!empty($catalog)): ?>
					<?php foreach ($catalog as $item): ?>
						<article class="product-card">
							<div class="product-art">
								<img src="<?php echo !empty($item['image']) ? htmlspecialchars($item['image']) : 'images/thumbnail.png'; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width:100%;height:100%;object-fit:cover;">
							</div>
							<div class="product-copy">
								<h3><?php echo htmlspecialchars($item['name']); ?></h3>
								<p><?php echo htmlspecialchars(mb_substr($item['description'], 0, 140)); ?></p>
								<div class="badge-row">
									<span class="badge"><?php echo htmlspecialchars($item['category']); ?></span>
								</div>
								<div class="product-meta">
									<span class="price">$<?php echo number_format($item['price'], 2); ?></span>
									<a href="ptoduct.php?id=<?php echo urlencode($item['slug']); ?>" class="btn btn-small">View</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="panel full-col">
						<h3>No items available</h3>
						<p><?php echo $selectedCategory !== '' ? 'There are no products in this category yet.' : 'The catalog is empty right now. Admins can add products from the Add Products page.'; ?></p>
					</div>
				<?php endif; ?>
			</div>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>