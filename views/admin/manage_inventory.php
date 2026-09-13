<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('admin')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Manage Inventory - PawCare</title>
    <link rel="stylesheet" href="../assets/css/manage-inventory.css?v=3">
</head>
<body>

<div class="management-page">

    <aside class="management-sidebar">
        <h1>▣ Manage Stock</h1>
        <p class="sidebar-text">Select an item from the catalog, then add stock from here.</p>

        <?php if (!empty($success_message)): ?>
            <div class="inventory-message success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="inventory-message error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST" id="inventoryForm">

            <div class="field">
                <label>Inventory Type</label>
                <select id="inventoryType" name="inventory_type">
                    <option value="pet">Pet</option>
                    <option value="product">Product</option>
                </select>
            </div>

            <div class="field">
                <label>Category</label>
                <select id="categorySelect">
                    <option value="">Select Category</option>
                </select>
            </div>

            <div class="field">
                <label>Selected Item</label>
                <select id="itemSelect" name="item_id" required>
                    <option value="">Select Item</option>
                </select>
            </div>

            <div class="field">
                <label>Price (BDT)</label>
                <input type="number" id="itemPrice" name="price" min="0.01" step="0.01" placeholder="Price" required>
            </div>

            <div class="field">
                <label>Quantity to Add</label>
                <input type="number" id="stockQuantity" name="quantity" value="1" min="1" required>
            </div>

            <button type="submit" name="add_inventory" class="add-inventory-btn">+ ADD STOCK</button>
        </form>

        <div class="managing-status">
            <div>🌐</div>
            <span>PAWCARE INVENTORY</span>
        </div>
    </aside>

    <main class="management-content">

        <header class="management-header">
            <div>
                <strong>▣ PAWCARE INVENTORY MANAGEMENT</strong>
                <small>Manage pets and products from one place</small>
            </div>

            <div class="header-time">
                <span id="currentDate"></span> |
                <span id="currentTime"></span>
            </div>
        </header>

        <section class="catalog-section">
            <div class="section-heading">
                <div>
                    <h3>SELECT FROM CATALOG</h3>
                    <p>Choose a pet or product to manage its stock.</p>
                </div>

                <div class="catalog-tabs">
                    <button type="button" class="catalog-tab active" data-type="pet">Pets</button>
                    <button type="button" class="catalog-tab" data-type="product">Products</button>
                </div>
            </div>

            <div class="catalog-grid" id="petCatalog">
                <?php foreach ($pets as $pet): ?>
                    <div class="catalog-card"
                         data-type="pet"
                         data-id="<?= (int)$pet["pet_id"] ?>"
                         data-category="<?= htmlspecialchars($pet["category_name"] ?? "") ?>"
                         data-name="<?= htmlspecialchars($pet["pet_name"]) ?>"
                         data-price="<?= (float)$pet["price"] ?>"
                         data-stock="<?= (int)$pet["stock"] ?>">

                        <img src="../assets/uploads/pets/<?= htmlspecialchars($pet["image"] ?? "default_pet.jpg") ?>" alt="<?= htmlspecialchars($pet["pet_name"]) ?>">

                        <div class="catalog-card-info">
                            <p><?= htmlspecialchars($pet["pet_name"]) ?></p>
                            <span>Stock: <?= (int)$pet["stock"] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="catalog-grid hidden" id="productCatalog">
                <?php foreach ($products as $product): ?>
                    <div class="catalog-card"
                         data-type="product"
                         data-id="<?= (int)$product["product_id"] ?>"
                         data-category="<?= htmlspecialchars($product["category_name"] ?? "") ?>"
                         data-name="<?= htmlspecialchars($product["product_name"]) ?>"
                         data-price="<?= (float)$product["price"] ?>"
                         data-stock="<?= (int)$product["stock"] ?>">

                        <img src="../assets/uploads/products/<?= htmlspecialchars($product["image"] ?? "default_product.jpg") ?>" alt="<?= htmlspecialchars($product["product_name"]) ?>">

                        <div class="catalog-card-info">
                            <p><?= htmlspecialchars($product["product_name"]) ?></p>
                            <span>Stock: <?= (int)$product["stock"] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="selected-preview">
            <div class="preview-image" id="previewImage">
                <span>🐾</span>
            </div>

            <div class="preview-info">
                <span class="selected-label">SELECTED ITEM</span>
                <h2 id="selectedName">No item selected</h2>
                <strong id="selectedStock">Database Stock: -- Units</strong>
                <p id="selectedCategory">Category: --</p>
                <p id="selectedPrice">Price: --</p>
            </div>

            <div class="remove-panel">
                <h4>Remove Stock</h4>
                <p>Enter how many units you want to remove.</p>

            <form method="POST" id="removeInventoryForm">
                <input type="hidden" name="remove_type" id="removeInventoryType" value="">
                <input type="hidden" name="remove_item_id" id="removeItemId" value="">

                <label>Quantity to Remove</label>
                <input type="number" name="remove_quantity" id="removeQuantity" min="1" value="1" required>

                <button type="submit" name="remove_inventory" value="1" class="remove-btn">− REMOVE STOCK</button>
            </form>
            </div>
        </section>

        <section class="live-monitor">
            <div class="monitor-title">
                <div>
                    <h3>LIVE INVENTORY MONITOR</h3>
                    <p>Selected inventory information</p>
                </div>
            </div>

            <div class="monitor-placeholder" id="monitorContent">
                Select an item from the catalog to view its information.
            </div>
        </section>

        <div class="management-actions">
            <a href="<?php echo htmlspecialchars(route_url("admin/inventory")); ?>" class="back-btn">← INVENTORY OVERVIEW</a>
            <a href="<?php echo htmlspecialchars(route_url("admin/dashboard_overview")); ?>" class="back-btn">▣ ADMIN DASHBOARD</a>
        </div>

    </main>

</div>

<script src="../assets/js/admin-dashboard.js"></script>
<script src="../assets/js/manage-inventory.js"></script>

</body>
</html>
