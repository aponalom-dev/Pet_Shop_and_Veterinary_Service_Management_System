<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Customer Profile | PawCare</title>
    <link rel="stylesheet" href="../assets/css/customer-profile.css">
</head>

<body>

<div class="profile-page">

    <header class="profile-header">

        <div class="profile-brand">PawCare</div>

        <nav class="profile-nav">
            <a href="<?php echo htmlspecialchars(route_url("customer/dashboard")); ?>">Dashboard</a>
            <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>" class="active">Profile</a>
        </nav>

        <div class="profile-header-right">

            <div class="profile-search">
                <input type="text" placeholder="Search...">
            </div>

            <div class="header-avatar">
                <?php echo strtoupper(substr($customer["username"], 0, 1)); ?>
            </div>

        </div>

    </header>

    <?php if (isset($_GET["updated"]) && $_GET["updated"] === "1"): ?>

        <script>
            alert("Profile updated successfully!");
        </script>

    <?php endif; ?>

    <?php if (isset($_GET["password_changed"]) && $_GET["password_changed"] === "1"): ?>

        <script>
            alert("Password changed successfully!");
        </script>

    <?php endif; ?>

    <main class="profile-content">

        <aside class="profile-left">

            <section class="profile-card">

                <div class="profile-avatar-large">
                    <?php echo strtoupper(substr($customer["username"], 0, 1)); ?>
                </div>

                <h1><?php echo strtoupper(htmlspecialchars($customer["full_name"])); ?></h1>

                <p class="profile-username">
                    @<?php echo htmlspecialchars($customer["username"]); ?>
                </p>

                <span class="customer-badge">CUSTOMER</span>

                <div class="profile-actions">
                    <a href="<?php echo htmlspecialchars(route_url("customer/edit_profile")); ?>" class="edit-profile-btn">✎ Edit Profile</a>
                    <a href="<?php echo htmlspecialchars(route_url("customer/change_password")); ?>" class="change-password-btn">🔒 Change Password</a>
                    <a href="<?php echo htmlspecialchars(route_url("logout")); ?>" class="logout-btn">Logout</a>
                </div>

            </section>

            <section class="profile-shop-card">
                <img src="../assets/images/petLogo.jpeg" alt="PawCare Logo">
                <h2>🐾 PAWCARE</h2>
            </section>

        </aside>

        <section class="profile-right">

            <section class="information-panel">

                <div class="panel-title">👤 Personal Information</div>

                <div class="information-grid">

                    <div class="info-box">
                        <label>EMAIL ADDRESS</label>
                        <div>
                            ✉ <?php echo htmlspecialchars($customer["email"]); ?>
                        </div>
                    </div>

                    <div class="info-box">
                        <label>PHONE NUMBER</label>
                        <div>
                            ☎ <?php echo htmlspecialchars($customer["phone"] ?? ""); ?>
                        </div>
                    </div>

                    <div class="info-box full-width">
                        <label>SHIPPING ADDRESS</label>
                        <div>
                            📍 <?php echo htmlspecialchars($customer["address"] ?? ""); ?>
                        </div>
                    </div>

                </div>

            </section>

            <section class="recent-orders-panel">

                <div class="recent-orders-header">
                    <h2>🚚 Recent Orders</h2>
                    <a href="<?php echo htmlspecialchars(route_url("customer/orders")); ?>">View All</a>
                </div>

                <div class="recent-orders-list">

                    <?php if (empty($recent_orders)): ?>

                        <div class="no-orders">
                            No orders found yet.
                        </div>

                    <?php else: ?>

                        <?php foreach ($recent_orders as $order): ?>

                            <article class="recent-order-card">

                                <div class="recent-order-column">
                                    <span class="recent-order-label">Order ID</span>
                                    <strong>#<?php echo (int) $order["order_id"]; ?></strong>
                                </div>

                                <div class="recent-order-column">
                                    <span class="recent-order-label">Date</span>
                                    <strong><?php echo date("d M Y", strtotime($order["order_date"])); ?></strong>
                                </div>

                                <div class="recent-order-column">
                                    <span class="recent-order-label">Total</span>
                                    <strong class="recent-order-price">৳<?php echo number_format($order["total_amount"], 2); ?></strong>
                                </div>

                                <div class="recent-order-column">
                                    <span class="recent-order-label">Status</span>
                                    <span class="recent-order-status"><?php echo htmlspecialchars($order["order_status"]); ?></span>
                                </div>

                                <div class="recent-order-methods">
                                    <span>🚚 <?php echo htmlspecialchars($order["delivery_method"]); ?></span>
                                    <span>💳 <?php echo htmlspecialchars($order["payment_method"]); ?></span>
                                </div>

                                <a href="<?php echo htmlspecialchars(route_url("customer/order_details")); ?>&amp;order_id=<?php echo (int) $order["order_id"]; ?>" class="recent-order-details-btn">
                                    [ VIEW DETAILS ]
                                </a>

                            </article>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </section>

        </section>

    </main>

    <footer class="profile-footer">
        <span>PawCare</span>

        <span>
            © 2026 PawCare Systems.
            Professional Pet Management.
        </span>

        <span>Privacy Policy · Help Desk</span>
    </footer>

</div>

</body>

</html>
