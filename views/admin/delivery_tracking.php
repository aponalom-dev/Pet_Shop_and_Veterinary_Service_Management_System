<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('admin')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Delivery Tracking - PawCare</title>
    <link rel="stylesheet" href="../assets/css/admin-delivery.css">
</head>
<body>

<div class="admin-page">

    <header class="top-header">
        <div>
            <h1>WELCOME BACK, MASTER!</h1>
            <p>✦ Your Pet Empire is under your command, King!</p>
        </div>

        <div class="date-time">
            <div id="currentDate"></div>
            <div id="currentTime"></div>
        </div>
    </header>

    <div class="admin-layout">

        <aside class="sidebar">
            <div class="logo-area">
                <img src="../assets/images/petLogo.jpeg" alt="PawCare Logo">
                <h3>🐾 PET SHOP</h3>
            </div>

            <nav>
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>">Manage Accounts</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard_overview")); ?>">⌁ Dashboard Overview</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/inventory")); ?>">▤ Inventory Stock</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/sales_analytics")); ?>">💰 Sales Analytics</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/delivery_tracking")); ?>" class="active">🚚 Delivery Tracking</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/customer_reviews")); ?>" class="active">★ Customer Reviews</a>
            </nav>
        </aside>

        <main class="main-content">

            <h2 class="page-title">LOGISTICS INTELLIGENCE PARTNER HUB</h2>

            <?php if ($success_message): ?>
                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <section class="delivery-top">

                <div class="analytics-card">
                    <h3>PARTNER ANALYTICS</h3>

                    <div class="partner-analytics">

                        <div class="donut-chart" id="deliveryChart">
                            <div class="donut-center">
                                <span id="deliveryTotal"><?php echo $total_orders; ?></span>
                                <small>TOTAL ORDERS</small>
                            </div>
                        </div>

                        <div class="partner-legend">
                            <?php foreach ($partner_stats as $partner_name => $data): ?>

                                <?php
                                $share = $total_orders > 0 ? round(($data["orders"] / $total_orders) * 100) : 0;
                                ?>

                                <div class="legend-row">
                                    <span class="legend-color"></span>

                                    <div>
                                        <strong><?php echo htmlspecialchars($partner_name); ?></strong>
                                        <p><?php echo $share; ?>% Share · <?php echo $data["orders"]; ?> Orders</p>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>

                <div class="action-area">

                    <div class="action-card register-card">
                        <h3>REGISTER NEW CONTRACT</h3>
                        <input type="text" placeholder="Partner or contract name">
                        <button type="button">SEND ADD REQUEST</button>
                    </div>

                    <div class="action-card terminate-card">
                        <h3>TERMINATE CONTRACT</h3>
                        <input type="text" placeholder="Partner or contract name">
                        <button type="button">SEND TERMINATE REQUEST</button>
                    </div>

                </div>

            </section>

            <section class="metrics-section">
                <h3>REAL-TIME LOGISTICS PARTNER METRICS</h3>

                <div class="metrics-grid">

                    <?php foreach ($partner_stats as $partner_name => $data): ?>

                        <div class="metric-card">
                            <span><?php echo htmlspecialchars($partner_name); ?></span>
                            <h2><?php echo $data["delivered"]; ?></h2>
                            <p>COMPLETED JOBS</p>
                            <small><?php echo $data["orders"]; ?> Total Orders</small>
                        </div>

                    <?php endforeach; ?>

                </div>
            </section>

            <section class="tracking-section">

                <div class="tracking-heading">
                    <h3>ACTIVE DELIVERY TRACKING</h3>
                    <span><?php echo count($deliveries); ?> Records</span>
                </div>

                <div class="tracking-table-wrap">

                    <table>
                        <thead>
                            <tr>
                                <th>Delivery</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Agent</th>
                                <th>Partner</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Update</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if (count($deliveries) > 0): ?>

                            <?php foreach ($deliveries as $delivery): ?>

                                <tr>
                                    <td>#<?php echo $delivery["delivery_id"]; ?></td>
                                    <td>#<?php echo $delivery["order_id"]; ?></td>
                                    <td><?php echo htmlspecialchars($delivery["customer_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($delivery["agent_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($delivery["delivery_method"]); ?></td>
                                    <td>৳<?php echo number_format($delivery["total_amount"], 2); ?></td>

                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(" ", "-", $delivery["delivery_status"])); ?>">
                                            <?php echo htmlspecialchars($delivery["delivery_status"]); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <form method="POST" class="status-form">
                                            <input type="hidden" name="delivery_id" value="<?php echo $delivery["delivery_id"]; ?>">

                                            <select name="delivery_status">
                                                <option value="Assigned" <?php if ($delivery["delivery_status"] === "Assigned") echo "selected"; ?>>Assigned</option>
                                                <option value="Picked Up" <?php if ($delivery["delivery_status"] === "Picked Up") echo "selected"; ?>>Picked Up</option>
                                                <option value="Out for Delivery" <?php if ($delivery["delivery_status"] === "Out for Delivery") echo "selected"; ?>>Out for Delivery</option>
                                                <option value="Delivered" <?php if ($delivery["delivery_status"] === "Delivered") echo "selected"; ?>>Delivered</option>
                                                <option value="Failed" <?php if ($delivery["delivery_status"] === "Failed") echo "selected"; ?>>Failed</option>
                                                <option value="Cancelled" <?php if ($delivery["delivery_status"] === "Cancelled") echo "selected"; ?>>Cancelled</option>
                                            </select>

                                            <button type="submit" name="update_delivery">UPDATE</button>
                                        </form>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" class="empty-row">No delivery records found.</td>
                            </tr>

                        <?php endif; ?>

                        </tbody>
                    </table>

                </div>

            </section>

        </main>

    </div>

    <footer>
        🔥 POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
    </footer>

</div>

<script>
const deliveryPartnerNames = <?php echo json_encode($partner_names); ?>;
const deliveryPartnerValues = <?php echo json_encode($partner_values); ?>;
</script>

<script src="../assets/js/admin-dashboard.js"></script>
<script src="../assets/js/admin-delivery.js"></script>

</body>
</html>
