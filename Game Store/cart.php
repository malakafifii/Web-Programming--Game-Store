<?php include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<section class="page-hero">
			<p class="eyebrow">Cart</p>
			<h1>Your selected items.</h1>
			<p>Keep the cart lightweight and easy to scan, with totals, promos, and checkout steps clearly separated.</p>
		</section>

		<section class="content-grid">
			<?php
			$cart = $_SESSION['cart'] ?? [];
			$subtotal = 0;
			foreach ($cart as $item) {
				$subtotal += $item['price'] * $item['qty'];
			}
			$shipping = $subtotal > 0 ? 0 : 0;
			$total = $subtotal + $shipping;
			?>

			<article class="summary-panel two-col">
				<h2>Items in cart</h2>
				<?php if (empty($cart)): ?>
					<p>Your cart is empty. Add products from the store to see them here.</p>
				<?php else: ?>
					<?php foreach ($cart as $item): ?>
						<div class="cart-line">
							<strong><?php echo htmlspecialchars($item['name']); ?></strong>
							<span>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
						</div>
						<div class="cart-line">
							<small>Qty: <?php echo $item['qty']; ?></small>
						</div>
					<?php endforeach; ?>
					<div class="cart-line"><strong>Shipping estimate</strong><span><?php echo $shipping === 0 ? 'Free' : '$' . number_format($shipping, 2); ?></span></div>
				<?php endif; ?>
			</article>

			<article class="summary-panel two-col">
				<h2>Order summary</h2>
				<div class="checkout-line"><span>Subtotal</span><strong>$<?php echo number_format($subtotal, 2); ?></strong></div>
				<div class="checkout-line"><span>Discount</span><strong>$0.00</strong></div>
				<div class="checkout-line"><span>Total</span><strong>$<?php echo number_format($total, 2); ?></strong></div>
				<div class="form-actions">
					<a class="btn btn-primary" href="checkout.php">Checkout</a>
					<a class="btn btn-secondary" href="products.php">Continue Shopping</a>
				</div>
			</article>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>