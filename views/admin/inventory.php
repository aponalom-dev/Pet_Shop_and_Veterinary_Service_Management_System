<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('admin')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Inventory Stock - PawCare</title>
    <link rel="stylesheet" href="../assets/css/admin-inventory.css">
</head>
<body>

<div class="admin-page">

    <header class="top-header">
        <div>
            <h1>WELCOME BACK, MASTER!</h1>
            <p>👑 Your PawCare Kingdom is under your command!</p>
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
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard_overview")); ?>">▣ Dashboard Overview</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/inventory")); ?>" class="active">▤ Inventory Stock</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/sales_analytics")); ?>" class="active">💰 Sales Analytics</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/delivery_tracking")); ?>">🚚 Delivery Tracking</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/customer_reviews")); ?>" class="active">★ Customer Reviews</a>
            </nav>
        </aside>

        <main class="main-content">

            <div class="inventory-heading">
                <div>
                    <h2>📦 INVENTORY STOCK</h2>
                    <p>Manage and monitor PawCare pets and products</p>
                </div>

                <div class="inventory-tools">
                    <input type="text" id="inventorySearch" data-search-url="<?php echo htmlspecialchars(route_url("ajax", "action=search_inventory")); ?>" placeholder="Search inventory...">
                    <a href="<?php echo htmlspecialchars(route_url("admin/manage_inventory")); ?>" class="manage-btn">+ Manage Inventory</a>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <span>🐾</span>
                    <div>
                        <strong><?= $total_pet_stock ?></strong>
                        <p>Pet Stock</p>
                    </div>
                </div>

                <div class="summary-card">
                    <span>📦</span>
                    <div>
                        <strong><?= $total_product_stock ?></strong>
                        <p>Product Stock</p>
                    </div>
                </div>

                <div class="summary-card">
                    <span>⚠️</span>
                    <div>
                        <strong><?= $low_stock ?></strong>
                        <p>Low Stock</p>
                    </div>
                </div>

                <div class="summary-card">
                    <span>❌</span>
                    <div>
                        <strong><?= $out_of_stock ?></strong>
                        <p>Out of Stock</p>
                    </div>
                </div>
            </div>

            <div class="inventory-tabs">
                <button type="button" class="inventory-tab active" data-target="petsTable">Pets</button>
                <button type="button" class="inventory-tab" data-target="productsTable">Products</button>
            </div>

            <section class="inventory-panel active-panel" id="petsTable">
                <div class="panel-title">
                    <h3>🐾 Pet Inventory</h3>
                    <span id="petInventoryCount"><?= count($pets) ?> Records</span>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pet</th>
                                <th>Category</th>
                                <th>Breed</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="petInventoryRows">
                            <?php if (!empty($pets)): ?>
                                <?php foreach ($pets as $pet): ?>
                                    <?php
                                    $stock = (int)$pet["stock"];
                                    $stock_class = $stock === 0 ? "out-stock" : ($stock <= 5 ? "low-stock" : "good-stock");
                                    ?>
                                    <tr class="inventory-row">
                                        <td>#<?= (int)$pet["pet_id"] ?></td>
                                        <td><?= htmlspecialchars($pet["pet_name"]) ?></td>
                                        <td><?= htmlspecialchars($pet["category_name"] ?? "N/A") ?></td>
                                        <td><?= htmlspecialchars($pet["breed"] ?? "N/A") ?></td>
                                        <td>৳<?= number_format((float)$pet["price"], 2) ?></td>
                                        <td><span class="stock-badge <?= $stock_class ?>"><?= $stock ?></span></td>
                                        <td><?= htmlspecialchars($pet["status"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="empty-message">No pets found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="inventory-panel" id="productsTable">
                <div class="panel-title">
                    <h3>📦 Product Inventory</h3>
                    <span id="productInventoryCount"><?= count($products) ?> Records</span>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="productInventoryRows">
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <?php
                                    $stock = (int)$product["stock"];
                                    $stock_class = $stock === 0 ? "out-stock" : ($stock <= 5 ? "low-stock" : "good-stock");
                                    ?>
                                    <tr class="inventory-row">
                                        <td>#<?= (int)$product["product_id"] ?></td>
                                        <td><?= htmlspecialchars($product["product_name"]) ?></td>
                                        <td><?= htmlspecialchars($product["category_name"] ?? "N/A") ?></td>
                                        <td>৳<?= number_format((float)$product["price"], 2) ?></td>
                                        <td><span class="stock-badge <?= $stock_class ?>"><?= $stock ?></span></td>
                                        <td><?= htmlspecialchars($product["status"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="empty-message">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>

    </div>

    <footer class="admin-footer">
        🛡 POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
    </footer>

</div>

<script src="../assets/js/admin-dashboard.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/admin-inventory.js"></script>

</body>
</html>
