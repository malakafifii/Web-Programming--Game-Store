<?php include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<?php
	$productSlug = isset($_GET['id']) ? trim($_GET['id']) : '';
	$product = $productSlug ? getProductBySlug($productSlug) : null;
	if (!$product) {
	    header('Location: products.php');
	    exit;
	}
	?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>

		<section class="page-hero">
			<p class="eyebrow">Product detail</p>
			<h1><?php echo htmlspecialchars($product['name']); ?></h1>
			<p><?php echo htmlspecialchars($product['short_description']); ?></p>
		</section>

		<section class="product-detail">
			<div class="detail-art"></div>
			<article class="panel">
				<h2><?php echo htmlspecialchars($product['name']); ?></h2>
				<div class="badge-row">
					<span class="badge"><?php echo htmlspecialchars($product['category']); ?></span>
					<span class="badge">In stock</span>
					<span class="badge">Free returns</span>
				</div>
				<p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
				<div class="product-meta" style="margin: 20px 0;">
					<span class="price">$<?php echo number_format($product['price'], 2); ?></span>
					<a href="?add_to_cart=1&id=<?php echo urlencode($product['slug']); ?>&product=<?php echo urlencode($product['name']); ?>&price=<?php echo urlencode($product['price']); ?>" class="btn btn-primary">Add to Cart</a>
				</div>
				<ul class="stack-list">
					<li><span>Category</span><strong><?php echo htmlspecialchars($product['category']); ?></strong></li>
					<li><span>SKU</span><strong><?php echo htmlspecialchars($product['sku']); ?></strong></li>
					<li><span>Details</span><strong>Fully supported by the online catalog.</strong></li>
				</ul>
			</article>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>