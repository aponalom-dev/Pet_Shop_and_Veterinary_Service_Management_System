<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('admin')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Customer Reviews - PawCare</title>
    <link rel="stylesheet" href="../assets/css/admin-reviews.css">
</head>
<body>

<div class="admin-page">

    <header class="top-header">
        <div>
            <h1>WELCOME BACK, MASTER!</h1>
            <p>♛ Your Pet Somrajjo is under your command, King!</p>
        </div>

        <div class="date-time">
            <div id="currentDate"></div>
            <div id="currentTime"></div>
        </div>
    </header>

    <div class="admin-layout">

        <aside class="sidebar">

            <div class="logo-area">
                <img src="../assets/images/petLogo.jpeg" alt="Pet Shop">
                <h3>🐾 PET SHOP</h3>
            </div>

            <nav>
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>">Manage Accounts</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard_overview")); ?>">⌁ Dashboard Overview</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/inventory")); ?>">▥ Inventory Stock</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/sales_analytics")); ?>">◔ Sales Analytics</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/delivery_tracking")); ?>">🚚 Delivery Tracking</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/customer_reviews")); ?>" class="active">★ Customer Reviews</a>
            </nav>

        </aside>

        <main class="main-content">

            <div class="welcome-strip">
                Welcome back, Boss! Your customers are talking about their pets today.
            </div>

            <?php if ($success_message): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="reviews-layout">

                <section class="reviews-column">

                    <?php if (count($reviews) > 0): ?>

                        <?php foreach ($reviews as $review): ?>

                            <div class="review-card">

                                <div class="review-avatar">
                                    <?php echo strtoupper(substr($review["customer_name"], 0, 1)); ?>
                                </div>

                                <div class="review-info">

                                    <div class="review-title">
                                        <h3><?php echo htmlspecialchars($review["customer_name"]); ?></h3>
                                        <span><?php echo date("d M", strtotime($review["created_at"])); ?></span>
                                    </div>

                                    <p class="review-product">
                                        <?php echo ucfirst($review["item_type"]); ?>:
                                        <?php echo htmlspecialchars($review["item_name"] ?? "Unknown Item"); ?>
                                    </p>

                                    <div class="stars">
                                        <?php
                                        foreach ([1, 2, 3, 4, 5] as $i) {
                                            echo $i <= $review["rating"] ? "★" : "☆";
                                        }
                                        ?>
                                    </div>

                                    <p class="review-comment">
                                        <?php echo htmlspecialchars($review["comment"] ?: "No written comment."); ?>
                                    </p>

                                    <div class="review-bottom">

                                        <span class="review-status <?php echo strtolower($review["status"]); ?>">
                                            <?php echo htmlspecialchars($review["status"]); ?>
                                        </span>

                                        <form method="POST">
                                            <input type="hidden" name="review_id" value="<?php echo $review["review_id"]; ?>">

                                            <?php if ($review["status"] === "Visible"): ?>
                                                <input type="hidden" name="status" value="Hidden">
                                                <button type="submit" name="update_review_status">Hide</button>
                                            <?php else: ?>
                                                <input type="hidden" name="status" value="Visible">
                                                <button type="submit" name="update_review_status">Show</button>
                                            <?php endif; ?>
                                        </form>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-reviews">
                            No customer reviews available.
                        </div>

                    <?php endif; ?>

                </section>

                <aside class="champion-panel">

                    <h3>🏆 CHAMPION PET</h3>

                    <?php if ($champion_pet): ?>

                        <?php
                        $champion_image = trim($champion_pet["image"] ?? "");

                        if (empty($champion_image)) {
                            $champion_image = "default_pet.jpg";
                        }
                        ?>

                        <div class="champion-image">
                            <img
                                src="../assets/uploads/pets/<?php echo htmlspecialchars($champion_image); ?>"
                                onerror="this.onerror=null; this.src='../assets/uploads/pets/default_pet.jpg';"
                                alt="<?php echo htmlspecialchars($champion_pet["pet_name"]); ?>"
                            >
                        </div>

                        <h2><?php echo strtoupper(htmlspecialchars($champion_pet["pet_name"])); ?></h2>

                        <p class="champion-breed">
                            <?php echo htmlspecialchars($champion_pet["breed"]); ?>
                        </p>

                        <div class="champion-stars">
                            ★ <?php echo number_format((float)$champion_pet["average_rating"], 1); ?>
                        </div>

                        <div class="champion-sales">
                            <?php echo (int)$champion_pet["total_sold"]; ?> Sold
                        </div>

                        <p class="champion-text">
                            Highest customer satisfaction and sales performance from the pet-lover community.
                        </p>

                        <small>
                            <?php echo (int)$champion_pet["total_reviews"]; ?> customer review(s)
                        </small>

                        <div class="champion-line"></div>

                    <?php else: ?>

                        <div class="no-champion">
                            <div>🐾</div>
                            <p>No pet review available yet.</p>
                        </div>

                    <?php endif; ?>

                </aside>

            </div>

        </main>

    </div>

    <footer>
        🔥 POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
    </footer>

</div>

<script src="../assets/js/admin-dashboard.js"></script>

</body>
</html>
