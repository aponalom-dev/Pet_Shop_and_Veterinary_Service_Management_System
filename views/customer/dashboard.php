<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Customer Dashboard | PawCare</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/customer-dashboard.css">
</head>

<body>

<div class="customer-dashboard">

    <header class="customer-header">

        <div class="customer-welcome">
            <h1>WELCOME, <?php echo strtoupper(htmlspecialchars($username)); ?>!</h1>
            <p>“To administer is the mark of a true master.”</p>

            <div class="dashboard-search">
                <input type="text" id="dashboardSearch" placeholder="Search pets or accessories...">
            </div>
        </div>

        <div class="header-center">
            <div class="quality-badge">🐾 QUALITY · CARE · PASSION 🐾</div>
            <div class="current-time" id="currentTime">00:00:00</div>
        </div>

        <div class="customer-profile">
            <span class="profile-name"><?php echo htmlspecialchars($username); ?></span>

            <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>" class="profile-avatar">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </a>
        </div>

    </header>

    <main class="dashboard-body">

        <aside class="customer-sidebar">

            <div class="sidebar-title">
                <span class="paw-box">🐾</span>
                <h2>PAWCARE</h2>
            </div>

            <nav class="customer-menu">
                <button class="dashboard-menu-btn active" data-filter="all">▦ Dashboard</button>
                <button class="dashboard-menu-btn" data-filter="best">📊 Best Sellers</button>
                <button class="dashboard-menu-btn" data-filter="cat">🐱 Cat</button>
                <button class="dashboard-menu-btn" data-filter="dog">🐶 Dog</button>
                <button class="dashboard-menu-btn" data-filter="rabbit">🐰 Rabbit</button>
                <button class="dashboard-menu-btn" data-filter="bird">🐦 Bird</button>
                <button class="dashboard-menu-btn" data-filter="food">🍖 Pet Food</button>
                <button class="dashboard-menu-btn" data-filter="accessories">🎾 Pet Accessories</button>
                <button class="dashboard-menu-btn" data-filter="medicine">💊 Pet Medicine</button>
            </nav>

        </aside>

        <section class="dashboard-content">

            <?php if (!empty($cart_message)): ?>

                <div id="cartToast" class="cart-toast <?php echo $cart_message_type === "success" ? "toast-success" : "toast-error"; ?>">

                    <span class="toast-icon">
                        <?php echo $cart_message_type === "success" ? "✓" : "✕"; ?>
                    </span>

                    <span><?php echo htmlspecialchars($cart_message); ?></span>

                    <button type="button" class="toast-close" id="toastClose">×</button>

                </div>

            <?php endif; ?>

            <div class="discover-banner">
                🐾 DISCOVER YOUR NEW BEST FRIEND 🐾
            </div>

            <div class="items-grid" id="itemsGrid">

                <?php foreach ($pets as $pet): ?>

                    <article
                        class="item-card"
                        data-id="<?php echo (int) $pet["pet_id"]; ?>"
                        data-type="pet"
                        data-category="<?php echo strtolower(htmlspecialchars($pet["category_name"])); ?>"
                        data-name="<?php echo strtolower(htmlspecialchars($pet["pet_name"])); ?>"
                        data-extra="<?php echo strtolower(htmlspecialchars($pet["breed"])); ?>">

                        <div class="item-image">
                            <img src="../assets/uploads/pets/<?php echo htmlspecialchars($pet["image"]); ?>" alt="<?php echo htmlspecialchars($pet["pet_name"]); ?>">
                        </div>

                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($pet["pet_name"]); ?></h3>
                            <p class="item-extra"><?php echo htmlspecialchars($pet["breed"]); ?></p>

                            <div class="item-price">
                                ৳ <?php echo number_format($pet["price"], 2); ?>
                            </div>

                            <div class="item-stock">
                                📦 In Stock: <?php echo (int) $pet["stock"]; ?>
                            </div>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="item_type" value="pet">
                            <input type="hidden" name="item_id" value="<?php echo (int) $pet["pet_id"]; ?>">
                            <button type="submit" class="add-bill-btn">ADD TO BILL</button>
                        </form>

                    </article>

                <?php endforeach; ?>

                <?php foreach ($products as $product): ?>

                    <article
                        class="item-card"
                        data-id="<?php echo (int) $product["product_id"]; ?>"
                        data-type="product"
                        data-category="<?php echo strtolower(htmlspecialchars($product["category_name"])); ?>"
                        data-name="<?php echo strtolower(htmlspecialchars($product["product_name"])); ?>"
                        data-extra="<?php echo strtolower(htmlspecialchars($product["brand"] ?? "")); ?>">

                        <div class="item-image">
                            <img src="../assets/uploads/products/<?php echo htmlspecialchars($product["image"]); ?>" alt="<?php echo htmlspecialchars($product["product_name"]); ?>">
                        </div>

                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($product["product_name"]); ?></h3>
                            <p class="item-extra"><?php echo htmlspecialchars($product["brand"] ?? ""); ?></p>

                            <div class="item-price">
                                ৳ <?php echo number_format($product["price"], 2); ?>
                            </div>

                            <div class="item-stock">
                                📦 In Stock: <?php echo (int) $product["stock"]; ?>
                            </div>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="item_type" value="product">
                            <input type="hidden" name="item_id" value="<?php echo (int) $product["product_id"]; ?>">
                            <button type="submit" class="add-bill-btn">ADD TO BILL</button>
                        </form>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

        <aside class="mini-cash-memo">

            <div class="cash-memo-title">MINI CASH MEMO</div>

            <div class="cash-memo-items" id="cashMemoItems">

                <?php if (empty($cart_items)): ?>

                    <div class="empty-cart-message">
                        No items added yet.
                    </div>

                <?php else: ?>

                    <?php foreach ($cart_items as $item): ?>

                        <div class="memo-item">

                            <div class="memo-item-details">
                                <strong><?php echo htmlspecialchars($item["item_name"]); ?></strong>
                                <small>Qty: <?php echo (int) $item["quantity"]; ?></small>
                                <span>৳ <?php echo number_format($item["subtotal"], 2); ?></span>
                            </div>

                            <form method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="cart_id" value="<?php echo (int) $item["cart_id"]; ?>">
                                <button type="submit" class="remove-memo-item">×</button>
                            </form>

                        </div>

                    <?php endforeach; ?>

                    <div class="memo-total">
                        <span>Total</span>
                        <strong>৳ <?php echo number_format($cart_total, 2); ?></strong>
                    </div>

                <?php endif; ?>

            </div>

            <div class="cash-memo-actions">

                <a href="<?php echo htmlspecialchars(route_url("customer/billing")); ?>" class="generate-bill-btn billing-link">
                    🧾 GENERATE BILL
                </a>

                <form method="POST" onsubmit="return confirm('Remove all items from the bill?');">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="cancel-bill-btn">CANCEL</button>
                </form>

            </div>

        </aside>

    </main>

    <footer class="customer-footer">
        Trusted by Pet Parents |
        Quality Guaranteed 🐾
    </footer>

</div>

<script>
const bestSellerItems = <?php echo json_encode($best_sellers); ?>;
</script>

<script src="../assets/js/customer-dashboard.js"></script>

</body>
</html>
