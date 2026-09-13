<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>My Orders | PawCare</title>
    <link rel="stylesheet" href="../assets/css/customer-orders.css">
</head>

<body>

<div class="orders-page">

    <header class="orders-header">
        <div>
            <h1>MY ORDERS</h1>
            <p>Complete PawCare order history</p>
        </div>

        <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>">← BACK TO PROFILE</a>
    </header>

    <main class="orders-content">

        <div class="orders-summary">
            <div>
                <span>Total Orders</span>
                <strong><?php echo count($orders); ?></strong>
            </div>
        </div>

        <?php if (empty($orders)): ?>

            <div class="empty-orders">
                <h2>No Orders Yet</h2>
                <p>Your PawCare orders will appear here.</p>
                <a href="<?php echo htmlspecialchars(route_url("customer/dashboard")); ?>">START SHOPPING</a>
            </div>

        <?php else: ?>

            <div class="orders-list">

                <?php foreach ($orders as $order): ?>

                    <article class="history-order-card">

                        <div class="order-main-info">

                            <div>
                                <small>ORDER ID</small>
                                <strong>#<?php echo (int) $order["order_id"]; ?></strong>
                            </div>

                            <div>
                                <small>DATE</small>
                                <strong><?php echo date("d M Y", strtotime($order["order_date"])); ?></strong>
                            </div>

                            <div>
                                <small>TOTAL</small>
                                <strong class="history-price">৳<?php echo number_format($order["total_amount"], 2); ?></strong>
                            </div>

                            <div>
                                <small>ORDER STATUS</small>
                                <span class="history-status"><?php echo htmlspecialchars($order["order_status"]); ?></span>
                            </div>

                        </div>

                        <div class="order-secondary-info">

                            <span>
                                🚚 <?php echo htmlspecialchars($order["delivery_method"]); ?>
                            </span>

                            <span>
                                💳 <?php echo htmlspecialchars($order["payment_method"]); ?>
                            </span>

                            <span>
                                Payment: <?php echo htmlspecialchars($order["payment_status"]); ?>
                            </span>

                        </div>

                        <div class="order-history-actions">
                            <a href="<?php echo htmlspecialchars(route_url("customer/order_details")); ?>&amp;order_id=<?php echo (int) $order["order_id"]; ?>">VIEW DETAILS</a>
                            <a href="<?php echo htmlspecialchars(route_url("customer/order_success")); ?>&amp;order_id=<?php echo (int) $order["order_id"]; ?>" class="receipt-history-btn">VIEW RECEIPT</a>
                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
