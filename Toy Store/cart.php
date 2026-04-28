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
			<article class="summary-panel two-col">
				<h2>Items in cart</h2>
				<div class="cart-line"><strong>Wireless Pro Controller</strong><span>$69.99</span></div>
				<div class="cart-line"><strong>RGB Gaming Headset</strong><span>$49.99</span></div>
				<div class="cart-line"><strong>Shipping estimate</strong><span>Free</span></div>
			</article>

			<article class="summary-panel two-col">
				<h2>Order summary</h2>
				<div class="checkout-line"><span>Subtotal</span><strong>$119.98</strong></div>
				<div class="checkout-line"><span>Discount</span><strong>-$10.00</strong></div>
				<div class="checkout-line"><span>Total</span><strong>$109.98</strong></div>
				<div class="form-actions">
					<a class="btn btn-primary" href="checkout.php">Checkout</a>
					<a class="btn btn-secondary" href="products.php">Continue Shopping</a>
				</div>
			</article>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>
