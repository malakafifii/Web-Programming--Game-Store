<?php include 'includes/header.php'; ?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<section class="page-hero">
			<p class="eyebrow">Checkout</p>
			<h1>Finish your order.</h1>
			<p>Use a simple step-based checkout with clean form fields and a compact order summary.</p>
		</section>

		<section class="content-grid">
			<form class="form-panel two-col" action="#" method="post">
				<h2>Billing details</h2>
				<div class="form-grid">
					<label class="field">Full name<input type="text" name="full_name" placeholder="Jordan Lee"></label>
					<label class="field">Phone<input type="tel" name="phone" placeholder="(555) 555-5555"></label>
					<label class="field">Email<input type="email" name="email" placeholder="you@example.com"></label>
					<label class="field">Payment<select name="payment"><option>Credit card</option><option>PayPal</option><option>Cash on pickup</option></select></label>
				</div>
				<label class="field" style="display:block; margin-top:14px;">Address<textarea name="address" placeholder="Street, city, state, zip"></textarea></label>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">Place Order</button>
					<a class="btn btn-secondary" href="cart.php">Back to Cart</a>
				</div>
			</form>

			<aside class="summary-panel two-col">
				<h2>Order summary</h2>
				<div class="checkout-line"><span>Items</span><strong>2</strong></div>
				<div class="checkout-line"><span>Estimated shipping</span><strong>Free</strong></div>
				<div class="checkout-line"><span>Estimated total</span><strong>$109.98</strong></div>
				<p class="muted">This section can be connected to a database or cart session later without changing the layout.</p>
			</aside>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>
