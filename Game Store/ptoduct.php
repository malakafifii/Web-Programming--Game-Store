<?php include 'includes/header.php';
require_once __DIR__ . '/includes/db.php';
?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<?php
	$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$product = $productId ? getProductById($productId) : null;
	if (!$product) {
	    header('Location: products.php');
	    exit;
	}
	
	// Handle delete request
	if (isset($_GET['delete']) && isAdmin()) {
	    $deleteId = (int) $_GET['delete'];
	    try {
	        delteProduct($deleteId);
	        $_SESSION['success_message'] = "Product deleted successfully!";
	        header("Location: products.php");
	        exit;
	    } catch (Throwable $e) {
	        $errorMessage = "Error deleting product: " . $e->getMessage();
	    }
	}
	?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>

		<section class="page-hero">
			<p class="eyebrow">Product detail</p>
			<h1><?php echo htmlspecialchars($product['name']); ?></h1>
			<p><?php echo htmlspecialchars(mb_substr($product['description'], 0, 180)); ?></p>
		</section>

		<section class="product-detail">
			<div class="detail-art">
				<img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'images/thumbnail.png'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%;height:auto;object-fit:contain;">
			</div>
			<article class="panel">
				<h2><?php echo htmlspecialchars($product['name']); ?></h2>
				<div class="badge-row">
					<span class="badge"><?php echo htmlspecialchars($product['category']); ?></span>
					<span class="badge"><?php echo ($product['stock_status'] ?? 'in_stock') === 'in_stock' ? 'In stock' : 'Out of stock'; ?></span>
					<span class="badge">Free returns</span>
				</div>
				<p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
				<?php $isOutOfStock = ($product['stock_status'] ?? 'in_stock') === 'out_of_stock'; ?>
				<div class="product-meta" style="margin: 20px 0;">
					<span class="price">$<?php echo number_format($product['price'], 2); ?></span>
				<div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
					<div style="display: flex; align-items: center; gap: 8px; border: 1px solid var(--border); border-radius: 8px; padding: 0 8px; opacity: <?php echo $isOutOfStock ? '0.5' : '1'; ?>;">
						<button type="button" onclick="<?php echo $isOutOfStock ? 'return false;' : "document.getElementById('qty-input').value = Math.max(1, parseInt(document.getElementById('qty-input').value) - 1)"; ?>" class="btn" style="background: none; border: none; padding: 8px; cursor: <?php echo $isOutOfStock ? 'not-allowed' : 'pointer'; ?>; font-size: 1.2rem;" <?php echo $isOutOfStock ? 'disabled' : ''; ?>>−</button>
						<input type="number" id="qty-input" value="1" min="1" style="width: 50px; text-align: center; border: none; background: none;" <?php echo $isOutOfStock ? 'disabled' : ''; ?> />
						<button type="button" onclick="<?php echo $isOutOfStock ? 'return false;' : "document.getElementById('qty-input').value = parseInt(document.getElementById('qty-input').value) + 1"; ?>" class="btn" style="background: none; border: none; padding: 8px; cursor: <?php echo $isOutOfStock ? 'not-allowed' : 'pointer'; ?>; font-size: 1.2rem;" <?php echo $isOutOfStock ? 'disabled' : ''; ?>>+</button>
					</div>
					<?php if ($isOutOfStock): ?>
						<button class="btn btn-primary" style="background: #ccc; color: #666; cursor: not-allowed;" disabled>Out of Stock</button>
					<?php else: ?>
						<a href="?add_to_cart=1&id=<?php echo urlencode($product['id']); ?>&product=<?php echo urlencode($product['name']); ?>&price=<?php echo urlencode($product['price']); ?>&qty=" onclick="this.href += document.getElementById('qty-input').value;" class="btn btn-primary">Add to Cart</a>
					<?php endif; ?>
					<?php if (isAdmin()): ?>
						<a href="admin.php?edit=<?php echo (int)$product['id']; ?>" class="btn btn-secondary">Edit</a>
						<a href="?delete=<?php echo (int)$product['id']; ?>" class="btn btn-secondary" style="background: #dc3545; border-color: #dc3545;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
					<?php endif; ?>
				</div>
				</div>
				<ul class="stack-list">
					<li><span>Category</span><strong><?php echo htmlspecialchars($product['category']); ?></strong></li>
					<li><span>Status</span><strong><?php echo ($product['stock_status'] ?? 'in_stock') === 'in_stock' ? 'In stock' : 'Out of stock'; ?></strong></li>
					<li><span>Details</span><strong>Fully supported by the online catalog.</strong></li>
				</ul>
			</article>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>