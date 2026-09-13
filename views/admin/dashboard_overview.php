<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('admin')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Dashboard Overview - PawCare</title>
    <link rel="stylesheet" href="../assets/css/admin-overview.css">
</head>
<body>

<div class="admin-page">

    <header class="top-header">
        <div>
            <h1>WELCOME BACK, MASTER!</h1>
            <p>👑 Your Pet Kingdom is under your command, King!</p>
        </div>

        <div class="header-time">
            <span id="currentDate"></span>
            <span id="currentTime"></span>
        </div>
    </header>

    <div class="admin-layout">

        <aside class="sidebar">
            <div class="brand-area">
                <div class="brand-logo">🐾</div>
                <h2>🐾 PET SHOP</h2>
            </div>

            <nav class="sidebar-menu">
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>">Manage Accounts</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard_overview")); ?>" class="active">▣ Dashboard Overview</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/inventory")); ?>">▤ Inventory Stock</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/sales_analytics")); ?>" class="active">💰 Sales Analytics</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/delivery_tracking")); ?>">🚚 Delivery Tracking</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/customer_reviews")); ?>" class="active">★ Customer Reviews</a>
            </nav>
        </aside>

        <main class="main-content">

            <div class="overview-heading">
                <h2>♟ SOMRAJJO OVERVIEW</h2>
                <p>Live Data: Connected to PawCare Database</p>
            </div>

            <div class="stat-grid" data-stats-url="<?php echo htmlspecialchars(route_url("ajax", "action=stats")); ?>">

                <div class="stat-card revenue-card">
                    <div class="stat-top">
                        <strong data-stat="total_revenue">৳<?= number_format((float)$total_revenue, 2) ?></strong>
                        <span>💰</span>
                    </div>
                    <p>TOTAL REVENUE</p>
                </div>

                <div class="stat-card orders-card">
                    <div class="stat-top">
                        <strong data-stat="total_orders"><?= (int)$total_orders ?></strong>
                        <span>📦</span>
                    </div>
                    <p>TOTAL ORDERS</p>
                </div>

                <div class="stat-card pets-card">
                    <div class="stat-top">
                        <strong data-stat="pets_in_stock"><?= (int)$pets_in_stock ?></strong>
                        <span>🐾</span>
                    </div>
                    <p>PETS IN STOCK</p>
                </div>

                <div class="stat-card stock-card">
                    <div class="stat-top">
                        <strong data-stat="low_stock"><?= (int)$low_stock ?></strong>
                        <span>🔥</span>
                    </div>
                    <p>LOW STOCK ALERTS</p>
                </div>

            </div>

            <section class="activity-panel">
                <div class="activity-title">⚡ RECENT EMPIRE ACTIVITIES (LIVE)</div>

                <div class="activity-table">
                    <div class="table-row table-head">
                        <span>INV ID</span>
                        <span>CUSTOMER</span>
                        <span>BILL</span>
                        <span>STATUS</span>
                    </div>

                    <?php if (!empty($recent_orders)): ?>
                        <?php foreach ($recent_orders as $order): ?>
                            <div class="table-row">
                                <span>INV-<?= (int)$order["order_id"] ?></span>
                                <span><?= htmlspecialchars($order["full_name"]) ?></span>
                                <span>৳<?= number_format((float)$order["total_amount"], 2) ?></span>
                                <span><?= htmlspecialchars(ucfirst($order["payment_status"])) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-row">No recent orders found.</div>
                    <?php endif; ?>
                </div>
            </section>

        </main>

    </div>

    <footer class="admin-footer">
        🛡 POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
    </footer>

</div>

<script src="../assets/js/admin-dashboard.js"></script>

</body>
</html>
