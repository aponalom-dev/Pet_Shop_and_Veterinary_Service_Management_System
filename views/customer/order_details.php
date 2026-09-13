<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Order Details | PawCare</title>
    <link rel="stylesheet" href="../assets/css/order-details.css">
</head>

<body>

<div class="order-details-page">

    <header class="order-details-header">
        <div>
            <h1>ORDER DETAILS</h1>
            <p>PawCare Customer Order Information</p>
        </div>

        <div class="header-order-id">
            ORDER
            <strong>#<?php echo $order["order_id"]; ?></strong>
        </div>
    </header>

    <main class="order-details-content">

        <section class="details-card">
            <h2>Order Overview</h2>

            <div class="overview-grid">
                <div>
                    <span>Order Date</span>
                    <strong><?php echo date("d M Y, h:i A", strtotime($order["order_date"])); ?></strong>
                </div>

                <div>
                    <span>Order Status</span>
                    <strong class="status-badge"><?php echo htmlspecialchars($order["order_status"]); ?></strong>
                </div>

                <div>
                    <span>Payment Status</span>
                    <strong><?php echo htmlspecialchars($order["payment_status"]); ?></strong>
                </div>

                <div>
                    <span>Total Amount</span>
                    <strong class="total-highlight">৳<?php echo number_format($order["total_amount"], 2); ?></strong>
                </div>
            </div>
        </section>

        <section class="details-card">
            <h2>Customer Information</h2>

            <div class="information-grid">
                <div>
                    <span>Full Name</span>
                    <strong><?php echo htmlspecialchars($order["full_name"]); ?></strong>
                </div>

                <div>
                    <span>Username</span>
                    <strong>@<?php echo htmlspecialchars($order["username"]); ?></strong>
                </div>

                <div>
                    <span>Email</span>
                    <strong><?php echo htmlspecialchars($order["email"]); ?></strong>
                </div>

                <div>
                    <span>Phone</span>
                    <strong><?php echo htmlspecialchars($order["phone"] ?? ""); ?></strong>
                </div>
            </div>
        </section>

        <section class="details-card">
            <h2>Delivery & Payment</h2>

            <div class="information-grid">
                <div>
                    <span>Delivery Method</span>
                    <strong><?php echo htmlspecialchars($order["delivery_method"]); ?></strong>
                </div>

                <div>
                    <span>Payment Method</span>
                    <strong><?php echo htmlspecialchars($order["payment_method"]); ?></strong>
                </div>

                <div class="full-width">
                    <span>Delivery Address</span>
                    <strong><?php echo htmlspecialchars($order["delivery_address"]); ?></strong>
                </div>
            </div>
        </section>

        <!-- ORDERED ITEMS -->
        <section class="details-card">
            <h2>Ordered Items</h2>

            <div class="table-wrapper">
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>TYPE</th>
                            <th>PRICE</th>
                            <th>QTY</th>
                            <th>SUBTOTAL</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item["item_name"]); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($item["item_type"])); ?></td>
                            <td>৳<?php echo number_format($item["price"], 2); ?></td>
                            <td><?php echo (int) $item["quantity"]; ?></td>
                            <td>৳<?php echo number_format($item["subtotal"], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="details-grand-total">
                <span>GRAND TOTAL</span>
                <strong>৳<?php echo number_format($order["total_amount"], 2); ?></strong>
            </div>
        </section>

        <div class="details-actions">
            <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>" class="back-profile-btn">← BACK TO PROFILE</a>
            <a href="<?php echo htmlspecialchars(route_url("customer/order_success")); ?>&amp;order_id=<?php echo $order["order_id"]; ?>" class="receipt-btn">VIEW RECEIPT</a>
        </div>

    </main>

</div>

</body>
</html>
