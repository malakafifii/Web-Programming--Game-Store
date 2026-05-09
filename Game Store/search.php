<?php include 'includes/header.php'; ?>
<?php $query = isset($_GET['q']) ? trim($_GET['q']) : ''; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>

		<section class="page-hero">
			<p class="eyebrow">Search</p>
			<h1>Results for <?php echo $query !== '' ? htmlspecialchars($query) : 'your search'; ?>.</h1>
			<p>Use this layout for keyword-based product discovery, and connect the results to your backend later if needed.</p>
		</section>

		<section class="content-grid">
			<article class="panel full-col">
				<h2>Suggested results</h2>
				<div class="product-grid">
					<?php
					$results = $query !== '' ? searchProducts($query) : [];
					if (empty($results)): ?>
						<p>No results found for "<?php echo htmlspecialchars($query); ?>".</p>
					<?php else:
						foreach ($results as $product): ?>
							<article class="product-card">
								<div class="product-art">
									<img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'images/thumbnail.png'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%;height:100%;object-fit:cover;">
								</div>
								<div class="product-copy">
									<h3><?php echo htmlspecialchars($product['name']); ?></h3>
									<p><?php echo htmlspecialchars(mb_substr($product['description'], 0, 140)); ?></p>
									<div class="product-meta">
										<span class="price">$<?php echo number_format($product['price'], 2); ?></span>
										<a href="ptoduct.php?id=<?php echo urlencode($product['slug']); ?>" class="btn btn-small">View</a>
									</div>
								</div>
							</article>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</article>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>