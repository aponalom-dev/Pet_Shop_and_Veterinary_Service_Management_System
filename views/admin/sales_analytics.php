<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('admin')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Sales Analytics - PawCare</title>
    <link rel="stylesheet" href="../assets/css/admin-sales.css">
</head>
<body>

<div class="admin-page">

    <header class="top-header">
        <div>
            <h1>WELCOME BACK, MASTER!</h1>
            <p>♛ Your Pet Empire is under your command, King!</p>
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
                <a href="<?php echo htmlspecialchars(route_url("admin/sales_analytics")); ?>" class="active">💰 Sales Analytics</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/delivery_tracking")); ?>">🚚 Delivery Tracking</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/customer_reviews")); ?>" class="active">★ Customer Reviews</a>
            </nav>
        </aside>

        <main class="main-content">

            <section class="summary-grid">
                <div class="summary-card">
                    <span>Total Revenue</span>
                    <h2>৳<?php echo number_format($total_revenue, 2); ?></h2>
                    <p>Paid orders only</p>
                </div>

                <div class="summary-card">
                    <span>Total Orders</span>
                    <h2><?php echo $total_orders; ?></h2>
                    <p>All customer orders</p>
                </div>

                <div class="summary-card">
                    <span>Paid Orders</span>
                    <h2><?php echo $paid_orders; ?></h2>
                    <p>Successfully paid</p>
                </div>

                <div class="summary-card">
                    <span>Average Order Value</span>
                    <h2>৳<?php echo number_format($average_order_value, 2); ?></h2>
                    <p>Average paid order</p>
                </div>
            </section>

            <section class="analytics-top">

                <div class="intelligence-panel">
                    <h2>♛ EMPIRE LIVE INTELLIGENCE</h2>

                    <div class="chart-area">
                        <div class="donut-chart" id="salesChart">
                            <div class="donut-center">
                                <span id="totalSalesCount">0</span>
                                <small>Units Sold</small>
                            </div>
                        </div>

                        <div class="chart-legend">
                            <?php if (count($category_sales) > 0): ?>
                                <?php foreach ($category_sales as $category): ?>
                                    <div class="legend-item">
                                        <span class="legend-dot"></span>
                                        <p>
                                            <?php echo htmlspecialchars($category["category_name"]); ?>
                                            <strong><?php echo $category["total_sold"]; ?></strong>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="empty-text">No paid sales data found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="champions-panel">
                    <h2>🏆 CHAMPIONS</h2>

                    <div class="champion-grid">
                        <?php if (count($top_items) > 0): ?>
                            <?php foreach (array_slice($top_items, 0, 4) as $item): ?>

                                <?php
                                if ($item["item_type"] === "pet") {
                                    $image_path = "../assets/uploads/pets/" . $item["item_image"];
                                } else {
                                    $image_path = "../assets/uploads/products/" . $item["item_image"];
                                }
                                ?>

                                <div class="champion-card">
                                    <div class="champ-label">CHAMP</div>
                                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($item["item_name"]); ?>">
                                    <h4><?php echo htmlspecialchars($item["item_name"]); ?></h4>
                                    <p><?php echo $item["total_sold"]; ?> Units Sold</p>
                                </div>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-text">No champion items available.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </section>

            <section class="performance-section">
                <h2>📊 TOP PERFORMANCE BY PRODUCT</h2>

                <div class="performance-box">

                    <?php if (count($top_items) > 0): ?>

                        <?php
                        $max_sold = max(array_column($top_items, "total_sold"));
                        ?>

                        <?php foreach ($top_items as $item): ?>

                            <?php
                            $percentage = $max_sold > 0 ? ($item["total_sold"] / $max_sold) * 100 : 0;
                            ?>

                            <div class="performance-item">
                                <div class="performance-header">
                                    <span>
                                        <?php echo strtoupper(htmlspecialchars($item["category_name"])); ?>:
                                        <?php echo strtoupper(htmlspecialchars($item["item_name"])); ?>
                                    </span>

                                    <strong><?php echo $item["total_sold"]; ?> Units Sold</strong>
                                </div>

                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: <?php echo $percentage; ?>%;"></div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <p class="empty-text">No performance data available.</p>

                    <?php endif; ?>

                </div>
            </section>
        </main>

    </div>

    <footer>
        🔥 POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
    </footer>

</div>

<script>
const salesCategoryNames = <?php echo json_encode($category_names); ?>;
const salesCategoryValues = <?php echo json_encode($category_values); ?>;
</script>

<script src="../assets/js/admin-dashboard.js"></script>
<script src="../assets/js/admin-sales.js"></script>
</body>
</html>
