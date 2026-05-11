<?php 
session_start();
require_once __DIR__ . '/includes/db.php';

$emailError = '';
$phoneError = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $payment = $_POST['payment'] ?? '';
    $address = $_POST['address'] ?? '';
    
    // Validate fields
    if (!empty($fullName) && !empty($phone) && !empty($email) && !empty($payment) && !empty($address)) {
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailError = 'Please enter a valid email address.';
		}
		
		if(strlen($phone)!=11){
			$phoneError = 'Must be 11 numbers';		
		}
		
		
		if(empty($emailError) && empty($phoneError)) {
	        // Generate order ID based on current timestamp
	        $orderId = (int)microtime(true) * 1000; // Millisecond precision for uniqueness

	        // Get cart data
	        $cart = $_SESSION['cart'] ?? [];
	        $subtotal = 0;
	        foreach ($cart as $item) {
	            $subtotal += $item['price'] * $item['qty'];
	        }
	        
	        $shipping = $subtotal > 0 ? 0 : 0;
	        $total = $subtotal + $shipping;
	        
	        // Store order in session
	        $_SESSION['last_order'] = [
	            'full_name' => $fullName,
	            'phone' => $phone,
	            'email' => $email,
	            'payment' => $payment,
	            'address' => $address,
	            'items' => $cart,
	            'subtotal' => $subtotal,
	            'shipping' => $shipping,
	            'total' => $total
	        ];
	        
	        // Clear cart
	        $_SESSION['cart'] = [];
	        
	        // Redirect to order success page
	        header('Location: order_success.php?order_id=' . $orderId);
	        exit;
		}
    }
}

include 'includes/header.php'; 
?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>
		
		<?php
		$cart = $_SESSION['cart'] ?? [];
		$subtotal = 0;
		$itemCount = 0;
		
		foreach ($cart as $item) {
			$subtotal += $item['price'] * $item['qty'];
			$itemCount += $item['qty'];
		}
		
		$shipping = $subtotal > 0 ? 0 : 0;
		$total = $subtotal + $shipping;
		?>
		
		<section class="page-hero">
			<p class="eyebrow">Checkout</p>
			<h1>Finish your order.</h1>
			<p>Use a simple step-based checkout with clean form fields and a compact order summary.</p>
		</section>

		<section class="content-grid">
			<?php if (empty($cart)): ?>
				<article class="panel full-col">
					<h2>Your cart is empty</h2>
					<p>Add products to your cart to proceed with checkout.</p>
					<a class="btn btn-primary" href="products.php">Continue Shopping</a>
				</article>
			<?php else: ?>
			<form class="form-panel two-col" action="checkout.php" method="post">
				<h2>Billing details</h2>
				<div class="form-grid">
					<label class="field">Full name<input type="text" name="full_name" placeholder="Jordan Lee" required></label>
					<label class="field">Phone<input type="tel" name="phone" placeholder="(555) 555-5555" required>
						<span class='error'> <?php echo $phoneError; ?> </span> 
						<!-- add red color to error -->
					</label>
					
					<label class="field">Email<input type="email" name="email" placeholder="you@example.com" required>
						<span class='error'> <?php echo $emailError; ?> </span>
					</label>
					<label class="field">Payment<select name="payment" required><option value="">Select payment method</option><option value="Credit card">Credit card</option><option value="PayPal">PayPal</option><option value="Cash on pickup">Cash on pickup</option></select></label>
				</div>
				<label class="field" style="display:block; margin-top:14px;">Address<textarea name="address" placeholder="Street, city, state, zip" required></textarea></label>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">Place Order</button>
					<a class="btn btn-secondary" href="cart.php">Back to Cart</a>
				</div>
			</form>

			<aside class="form-panel two-col">
				<h2>Order summary</h2>
				<?php foreach ($cart as $item): ?>
					<div class="checkout-line">
						<span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['qty']; ?>)</span>
						<strong>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></strong>
					</div>
				<?php endforeach; ?>
				<div class="checkout-line"><span>Subtotal</span><strong>$<?php echo number_format($subtotal, 2); ?></strong></div>
				<div class="checkout-line"><span>Estimated shipping</span><strong><?php echo $shipping === 0 ? 'Free' : '$' . number_format($shipping, 2); ?></strong></div>
				<div class="checkout-line"><span>Estimated total</span><strong>$<?php echo number_format($total, 2); ?></strong></div>
			</aside>
			<?php endif; ?>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>