<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Pet Reviews | PawCare</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pet_reviews.css">
</head>

<body>

<div class="pet-review-page">

    <header class="top-bar">

        <div class="top-title">
            <span>🐾</span>
            PawCare – Pet Shop and Veterinary Service Management System
        </div>

        <div class="window-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>

    </header>

    <div class="page-layout">

        <aside class="sidebar">

            <div class="sidebar-logo">
                <img src="assets/images/petLogo.jpeg" alt="PawCare Logo">
            </div>

            <div class="brand-name">
                <span>🐾</span>
                <h2>PAWCARE</h2>
            </div>

            <nav class="sidebar-menu">

                <a href="<?php echo htmlspecialchars(route_url("login")); ?>" class="sidebar-btn">
                    Sign In — All Roles
                </a>

                <a href="<?php echo htmlspecialchars(route_url("pet_reviews")); ?>" class="sidebar-btn active">
                    Pet Reviews
                </a>

                <a href="<?php echo htmlspecialchars(route_url("specialist_doctors")); ?>" class="sidebar-btn">
                    Specialist Doctors
                </a>

                <a href="<?php echo htmlspecialchars(route_url("delivery/index")); ?>" class="sidebar-btn">
                    Delivery Man Portal
                </a>

            </nav>

            <div class="license-area">
                <a href="#" class="sidebar-btn">
                    Pet License
                </a>
            </div>

        </aside>

        <main class="main-content">

            <div class="review-heading">
                <h1>🐾 Pet <span>Reviews</span></h1>
                <p>Share love, read experiences, build trust</p>
            </div>

            <div class="review-image-box">

                <?php if (file_exists($review_image)): ?>

                    <img src="<?php echo htmlspecialchars($review_image); ?>" alt="Pet Reviews Under Construction">

                <?php else: ?>

                    <div class="image-missing">

                        <h2>🐶 Oops! Our pets are still working on it!</h2>

                        <p>
                            The Pet Reviews feature is currently under construction.
                        </p>

                        <p>
                            It will be updated soon. Stay tuned! 🐾
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </main>

    </div>

    <footer class="footer">
        🐾 "Pets are not our whole life..." | PawCare © 2026
    </footer>

</div>

</body>

</html>
