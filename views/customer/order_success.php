<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Order Confirmed | PawCare</title>
    <link rel="stylesheet" href="../assets/css/order-success.css">
</head>

<body>

<div class="success-page">

    <div class="success-card">

        <div class="success-icon">✓</div>

        <h1>ORDER CONFIRMED</h1>

        <p class="success-message">
            Thank you for choosing PawCare!
            Your order has been placed successfully.
        </p>

        <div class="order-number">
            ORDER ID
            <strong>#<?php echo $order["order_id"]; ?></strong>
        </div>

        <div class="receipt">

            <div class="receipt-header">
                <h2>PAWCARE PET SHOP</h2>
                <p>Pet Shop & Veterinary Service</p>
                <p>------------------------------</p>
            </div>

            <div class="receipt-info">

                <p>
                    <span>Customer:</span>
                    <?php echo htmlspecialchars($order["full_name"]); ?>
                </p>

                <p>
                    <span>Phone:</span>
                    <?php echo htmlspecialchars($order["phone"] ?? ""); ?>
                </p>

                <p>
                    <span>Date:</span>
                    <?php echo htmlspecialchars($order["order_date"]); ?>
                </p>

                <p>
                    <span>Delivery:</span>
                    <?php echo htmlspecialchars($order["delivery_method"]); ?>
                </p>

                <p>
                    <span>Payment:</span>
                    <?php echo htmlspecialchars($order["payment_method"]); ?>
                </p>

                <p>
                    <span>Address:</span>
                    <?php echo htmlspecialchars($order["delivery_address"]); ?>
                </p>

            </div>

            <div class="receipt-line">--------------------------------</div>

            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>QTY</th>
                        <th>PRICE</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item["item_name"]); ?></td>
                        <td><?php echo (int) $item["quantity"]; ?>x</td>
                        <td>৳<?php echo number_format($item["subtotal"], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="receipt-line">--------------------------------</div>

            <div class="receipt-total">
                <span>TOTAL</span>
                <strong>৳<?php echo number_format($order["total_amount"], 2); ?></strong>
            </div>

            <div class="status-area">

                <div>
                    Payment Status
                    <strong><?php echo htmlspecialchars($order["payment_status"]); ?></strong>
                </div>

                <div>
                    Order Status
                    <strong><?php echo htmlspecialchars($order["order_status"]); ?></strong>
                </div>

            </div>

            <div class="receipt-footer">
                Thank you for shopping with PawCare 🐾
                <br>
                Keep your Order ID for future reference.
            </div>

        </div>

        <div class="success-actions">
            <a href="<?php echo htmlspecialchars(route_url("customer/dashboard")); ?>" class="dashboard-btn">← BACK TO DASHBOARD</a>
        </div>

    </div>

</div>

</body>

</html>
