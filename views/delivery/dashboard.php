<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('delivery')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Delivery Dashboard | PawCare</title>
    <link rel="stylesheet" href="../assets/css/delivery_dashboard.css">
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

            <a href="<?php echo htmlspecialchars(route_url("delivery/dashboard")); ?>" class="active">
                Dashboard
            </a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>">
                Assigned Orders
            </a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/history")); ?>">
                History
            </a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/profile")); ?>">
                Profile Settings
            </a>

        </nav>

        <div class="sidebar-logout">

            <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">
                Logout
            </a>

        </div>

    </aside>

    <div class="main-area">

        <header class="topbar">

            <div>

                <h3>
                    WELCOME BACK, <?php echo strtoupper(htmlspecialchars($agent_name)); ?>!
                </h3>

                <p>Ready for today's deliveries?</p>

            </div>

            <div class="topbar-time">

                <span id="currentDate"></span>
                <span id="currentTime"></span>

            </div>

        </header>

        <main class="content">

            <div class="page-heading">

                <div>

                    <h1>DELIVERY AGENT DASHBOARD</h1>

                    <p>
                        Manage your assigned deliveries and track today's progress.
                    </p>

                </div>

                <div class="connection-status">
                    Live Data: Connected to PawCare Database
                </div>

            </div>

            <section class="stats-grid">

                <div class="stat-card">

                    <div class="stat-title">
                        TOTAL ASSIGNED
                    </div>

                    <div class="stat-number">
                        <?php echo $total_assigned; ?>
                    </div>

                    <div class="stat-text">
                        Total delivery orders
                    </div>

                </div>

                <div class="stat-card">

                    <div class="stat-title">
                        OUT FOR DELIVERY
                    </div>

                    <div class="stat-number">
                        <?php echo $out_for_delivery; ?>
                    </div>

                    <div class="stat-text">
                        Currently on the way
                    </div>

                </div>

                <div class="stat-card">

                    <div class="stat-title">
                        DELIVERED TODAY
                    </div>

                    <div class="stat-number">
                        <?php echo $delivered_today; ?>
                    </div>

                    <div class="stat-text">
                        Completed today
                    </div>

                </div>

                <div class="stat-card">

                    <div class="stat-title">
                        SUCCESS RATE
                    </div>

                    <div class="stat-number">
                        <?php echo $success_rate; ?>%
                    </div>

                    <div class="stat-text">
                        Overall completed deliveries
                    </div>

                </div>

            </section>

            <section class="recent-orders">

                <div class="section-heading">

                    <div>

                        <h2>Recent Assigned Orders</h2>

                        <p>Your latest delivery assignments.</p>

                    </div>

                    <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="view-all-btn">
                        VIEW ALL ORDERS
                    </a>

                </div>

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>
                                <th>ORDER ID</th>
                                <th>CUSTOMER</th>
                                <th>ADDRESS</th>
                                <th>BILL</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php if (count($orders) > 0): ?>

                            <?php foreach ($orders as $order): ?>

                                <tr>

                                    <td>

                                        <span class="order-id">
                                            #<?php echo (int)$order["order_id"]; ?>
                                        </span>

                                    </td>

                                    <td>

                                        <span class="customer-name">
                                            <?php echo htmlspecialchars($order["customer_name"]); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <span class="address">
                                            <?php echo htmlspecialchars($order["delivery_address"]); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <span class="amount">
                                            <?php echo number_format($order["total_amount"], 2); ?> BDT
                                        </span>

                                    </td>

                                    <td>

                                        <?php
                                        $status_class = "status-default";

                                        if ($order["delivery_status"] === "Assigned") {
                                            $status_class = "status-assigned";
                                        } elseif ($order["delivery_status"] === "Out for Delivery") {
                                            $status_class = "status-out";
                                        } elseif ($order["delivery_status"] === "Delivered") {
                                            $status_class = "status-delivered";
                                        } elseif ($order["delivery_status"] === "Failed") {
                                            $status_class = "status-failed";
                                        } elseif ($order["delivery_status"] === "Cancelled") {
                                            $status_class = "status-cancelled";
                                        }
                                        ?>

                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($order["delivery_status"]); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <a href="<?php echo htmlspecialchars(route_url("delivery/order_details")); ?>&amp;id=<?php echo (int)$order["delivery_id"]; ?>" class="details-btn">
                                            VIEW DETAILS
                                        </a>

                                        <?php if ($order["delivery_status"] === "Assigned"): ?>

                                            <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="action-btn">
                                                START DELIVERY
                                            </a>

                                        <?php elseif ($order["delivery_status"] === "Out for Delivery"): ?>

                                            <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="action-btn">
                                                MARK DELIVERED
                                            </a>

                                        <?php elseif ($order["delivery_status"] === "Delivered"): ?>

                                            <span class="completed-text">
                                                COMPLETED
                                            </span>

                                        <?php elseif ($order["delivery_status"] === "Failed"): ?>

                                            <span class="failed-text">
                                                FAILED
                                            </span>

                                        <?php elseif ($order["delivery_status"] === "Cancelled"): ?>

                                            <span class="cancelled-text">
                                                CANCELLED
                                            </span>

                                        <?php else: ?>

                                            <span>-</span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="6" class="empty-data">
                                    No delivery orders found.
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
