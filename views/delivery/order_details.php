<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('delivery')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Order Details | PawCare</title>
    <link rel="stylesheet" href="../assets/css/delivery_order_details.css">
</head>

<body>

<div class="delivery-layout">

    <aside class="sidebar">

        <div class="sidebar-brand">

            <img src="../assets/images/petLogo.jpeg" alt="PawCare Logo">

            <h2>PAWCARE</h2>

            <p><?php echo htmlspecialchars($company_name); ?></p>

        </div>

        <nav class="sidebar-menu">

            <a href="<?php echo htmlspecialchars(route_url("delivery/dashboard")); ?>">Dashboard</a>
            <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="active">Assigned Orders</a>
            <a href="<?php echo htmlspecialchars(route_url("delivery/history")); ?>">History</a>
            <a href="<?php echo htmlspecialchars(route_url("delivery/profile")); ?>">Profile Settings</a>

        </nav>

        <div class="sidebar-logout">
            <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">Logout</a>
        </div>

    </aside>

    <div class="main-area">

        <header class="topbar">

            <div>

                <h3>
                    WELCOME BACK, <?php echo strtoupper(htmlspecialchars($agent_name)); ?>!
                </h3>

                <p>View assigned order information.</p>

            </div>

            <div class="topbar-time">

                <span id="currentDate"></span>
                <span id="currentTime"></span>

            </div>

        </header>

        <main class="content">

            <div class="page-heading">

                <div>

                    <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="back-link">
                        ← BACK TO ASSIGNED ORDERS
                    </a>

                    <h1>
                        Order #<?php echo (int)$order["order_id"]; ?>
                    </h1>

                    <p>Complete delivery and customer information.</p>

                </div>

                <span class="status-badge">
                    <?php echo htmlspecialchars($order["delivery_status"]); ?>
                </span>

            </div>

            <div class="details-grid">

                <section class="info-card">

                    <div class="card-title">
                        CUSTOMER INFORMATION
                    </div>

                    <div class="info-row">

                        <span>Customer Name</span>

                        <strong>
                            <?php echo htmlspecialchars($order["customer_name"]); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Phone Number</span>

                        <strong>
                            <?php echo htmlspecialchars($order["customer_phone"] ?: "Not provided"); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Email Address</span>

                        <strong>
                            <?php echo htmlspecialchars($order["customer_email"]); ?>
                        </strong>

                    </div>

                    <div class="info-row address-row">

                        <span>Delivery Address</span>

                        <strong>
                            <?php echo htmlspecialchars($order["delivery_address"]); ?>
                        </strong>

                    </div>

                </section>

                <section class="info-card">

                    <div class="card-title">
                        DELIVERY INFORMATION
                    </div>

                    <div class="info-row">

                        <span>Delivery Company</span>

                        <strong>
                            <?php echo htmlspecialchars($order["delivery_method"]); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Delivery Status</span>

                        <strong>
                            <?php echo htmlspecialchars($order["delivery_status"]); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Order Status</span>

                        <strong>
                            <?php echo htmlspecialchars($order["order_status"]); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Assigned At</span>

                        <strong>
                            <?php echo date("d M Y, h:i A", strtotime($order["assigned_at"])); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Delivered At</span>

                        <strong>
                            <?php echo !empty($order["delivered_at"]) ? date("d M Y, h:i A", strtotime($order["delivered_at"])) : "Not delivered yet"; ?>
                        </strong>

                    </div>

                </section>

                <section class="info-card">

                    <div class="card-title">
                        PAYMENT INFORMATION
                    </div>

                    <div class="info-row">

                        <span>Payment Method</span>

                        <strong>
                            <?php echo htmlspecialchars($order["payment_method"]); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Payment Status</span>

                        <strong>
                            <?php echo htmlspecialchars($order["payment_status"]); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Order Date</span>

                        <strong>
                            <?php echo date("d M Y, h:i A", strtotime($order["order_date"])); ?>
                        </strong>

                    </div>

                    <div class="info-row">

                        <span>Total Bill</span>

                        <strong class="total-amount">
                            <?php echo number_format($order["total_amount"], 2); ?> BDT
                        </strong>

                    </div>

                </section>

                <section class="info-card">

                    <div class="card-title">
                        DELIVERY NOTE
                    </div>

                    <div class="note-box">

                        <?php if (!empty($order["delivery_note"])): ?>

                            <?php echo nl2br(htmlspecialchars($order["delivery_note"])); ?>

                        <?php else: ?>

                            No delivery note added.

                        <?php endif; ?>

                    </div>

                </section>

            </div>

            <section class="items-card">

                <div class="card-title">
                    ORDER ITEMS
                </div>

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>
                                <th>ITEM</th>
                                <th>TYPE</th>
                                <th>PRICE</th>
                                <th>QUANTITY</th>
                                <th>SUBTOTAL</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php if (count($items) > 0): ?>

                            <?php foreach ($items as $item): ?>

                                <tr>

                                    <td>
                                        <?php echo htmlspecialchars($item["item_name"]); ?>
                                    </td>

                                    <td>
                                        <?php echo ucfirst(htmlspecialchars($item["item_type"])); ?>
                                    </td>

                                    <td>
                                        <?php echo number_format($item["price"], 2); ?> BDT
                                    </td>

                                    <td>
                                        <?php echo (int)$item["quantity"]; ?>
                                    </td>

                                    <td>
                                        <?php echo number_format($item["subtotal"], 2); ?> BDT
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="5" class="empty-data">
                                    No item information found for this order.
                                </td>

                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</div>

<footer class="delivery-footer">
    POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
</footer>

<script>

function updateDateTime() {
    const now = new Date();

    document.getElementById("currentDate").textContent = now.toLocaleDateString("en-US", {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric"
    });

    document.getElementById("currentTime").textContent = now.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit"
    });
}

updateDateTime();
setInterval(updateDateTime, 1000);

</script>

</body>

</html>
