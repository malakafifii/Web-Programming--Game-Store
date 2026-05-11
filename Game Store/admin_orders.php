<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$orders = getOrders();
$orderCount = count($orders);
$itemCount = 0;
foreach ($orders as $order) {
    $itemCount += (int) ($order['item_count'] ?? 0);
}

include 'includes/header.php';
?>

<div class="layout">
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-pane">
        <section class="page-hero">
            <p class="eyebrow">Admin</p>
            <h1>Orders dashboard.</h1>
            <p>Review every checkout stored in the database, with each order grouped into a single card and its items listed beneath it.</p>
        </section>

        <section class="content-grid">
            <article class="form-panel full-col">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 16px;">
                    <h2 style="margin: 0;">All orders</h2>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <div class="checkout-line" style="margin: 0; min-width: 160px;">
                            <span>Orders</span>
                            <strong><?php echo (int) $orderCount; ?></strong>
                        </div>
                        <div class="checkout-line" style="margin: 0; min-width: 160px;">
                            <span>Items sold</span>
                            <strong><?php echo (int) $itemCount; ?></strong>
                        </div>
                    </div>
                </div>

                <?php if (empty($orders)): ?>
                    <p>No orders have been saved yet.</p>
                <?php else: ?>
                    <div style="display: grid; gap: 16px;">
                        <?php foreach ($orders as $order): ?>
                            <article style="border: 1px solid var(--border); border-radius: 14px; padding: 16px; background: #fff;">
                                <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 14px;">
                                    <div>
                                        <h3 style="margin: 0 0 6px 0;">Order #<?php echo (int) $order['order_id']; ?></h3>
                                        <div style="color: var(--text-secondary); font-size: 0.95rem;">
                                            <?php echo htmlspecialchars(date('F j, Y g:i A', strtotime($order['date']))); ?>
                                        </div>
                                    </div>
                                    <div style="text-align: right; color: var(--text-secondary); font-size: 0.95rem;">
                                        <div><?php echo htmlspecialchars($order['email']); ?></div>
                                        <div><?php echo htmlspecialchars($order['payment']); ?></div>
                                    </div>
                                </div>

                                <div style="display: grid; gap: 8px;">
                                    <div style="font-weight: 600; margin-bottom: 2px;">Items</div>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="checkout-line" style="margin: 0;">
                                            <span><?php echo htmlspecialchars($item['product_name']); ?> x<?php echo (int) $item['quantity']; ?></span>
                                            <strong>$<?php echo number_format($item['line_total'], 2); ?></strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="checkout-line" style="margin: 14px 0 0 0;">
                                    <span>Total</span>
                                    <strong>$<?php echo number_format((float) $order['total'], 2); ?></strong>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>
        </section>
    </main>
</div>

<?php include 'includes/footer.php'; ?>