<?php 
session_start();
require_once __DIR__ . '/includes/db.php';

// Check if order ID was passed
if (empty($_GET['order_id'])) {
    header('Location: products.php');
    exit;
}

$orderToken = (string) $_GET['order_id'];
$orderData = $_SESSION['last_order'] ?? null;

if (!$orderData) {
    header('Location: products.php');
    exit;
}

$savedOrders = $_SESSION['saved_orders'] ?? [];
if (!isset($savedOrders[$orderToken])) {
	$orderStmt = db()->prepare('INSERT INTO orders (email, payment) VALUES (:email, :payment)');
	$itemStmt = db()->prepare(
		'INSERT INTO order_items (order_id, product_Id, quantity, price)
		 VALUES (:order_id, :product_id, :quantity, :price)'
	);

	db()->beginTransaction();
	try {
		$orderStmt->execute([
			'email' => $orderData['email'],
			'payment' => $orderData['payment'],
		]);

		$databaseOrderId = (int) db()->lastInsertId();

		foreach ($orderData['items'] as $item) {
			$productId = (int) ($item['id'] ?? 0);
			$quantity = (int) ($item['qty'] ?? 0);
			$price = (float) ($item['price'] ?? 0);

			if ($productId <= 0 || $quantity <= 0) {
				throw new RuntimeException('Invalid product information in the cart.');
			}

			$itemStmt->execute([
				'order_id' => $databaseOrderId,
				'product_id' => $productId,
				'quantity' => $quantity,
				'price' => $price,
			]);
		}

		db()->commit();
		$_SESSION['saved_orders'][$orderToken] = [
			'db_order_id' => $databaseOrderId,
		];
	} catch (Throwable $e) {
		if (db()->inTransaction()) {
			db()->rollBack();
		}
		http_response_code(500);
		echo '<pre>Failed to save order: ' . htmlspecialchars($e->getMessage()) . '</pre>';
		exit;
	}
}

$savedOrder = $savedOrders[$orderToken] ?? ($_SESSION['saved_orders'][$orderToken] ?? null);
$databaseOrderId = is_array($savedOrder) && isset($savedOrder['db_order_id']) ? (int) $savedOrder['db_order_id'] : null;
$displayOrderId = $databaseOrderId ?? $orderToken;

include 'includes/header.php';
?>

<div class="layout">
	<?php include 'includes/sidebar.php'; ?>

	<main class="main-pane">
		<?php include 'includes/inc.php'; ?>
		
		<section class="page-hero">
			<p class="eyebrow">Success</p>
			<h1>Your order has been placed!</h1>
			<p>Thank you for your purchase. Your order is being prepared for shipment.</p>
		</section>

		<section class="content-grid">
			<article class="form-panel two-col">
				<h2>Order details</h2>
				<div style="margin: 20px 0; padding: 16px; background: #f5f5f5; border-radius: 8px;">
					<p><strong>Order ID:</strong> <code style="font-family: monospace; background: #fff; padding: 4px 8px; border-radius: 4px;"><?php echo htmlspecialchars((string) $displayOrderId); ?></code></p>
					<p><strong>Date:</strong> <?php echo date('F j, Y g:i A'); ?></p>
					<p><strong>Name:</strong> <?php echo htmlspecialchars($orderData['full_name']); ?></p>
					<p><strong>Email:</strong> <?php echo htmlspecialchars($orderData['email']); ?></p>
					<p><strong>Phone:</strong> <?php echo htmlspecialchars($orderData['phone']); ?></p>
					<p><strong>Payment method:</strong> <?php echo htmlspecialchars($orderData['payment']); ?></p>
					<p style="white-space: pre-wrap;"><strong>Shipping address:</strong> <?php echo htmlspecialchars($orderData['address']); ?></p>
				</div>
			</article>

			<aside class="form-panel two-col">
				<h2>Order summary</h2>
				<?php foreach ($orderData['items'] as $item): ?>
					<div class="checkout-line">
						<span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['qty']; ?>)</span>
						<strong>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></strong>
					</div>
				<?php endforeach; ?>
				<div class="checkout-line"><span>Subtotal</span><strong>$<?php echo number_format($orderData['subtotal'], 2); ?></strong></div>
				<div class="checkout-line"><span>Estimated shipping</span><strong><?php echo $orderData['shipping'] === 0 ? 'Free' : '$' . number_format($orderData['shipping'], 2); ?></strong></div>
				<div class="checkout-line"><span>Total</span><strong>$<?php echo number_format($orderData['total'], 2); ?></strong></div>
				<div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border);">
					<a href="products.php" class="btn btn-primary" style="width: 100%; text-align: center;">Continue Shopping</a>
				</div>
			</aside>
		</section>
	</main>
</div>

<?php include 'includes/footer.php'; ?>
