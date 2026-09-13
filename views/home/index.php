<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>PawCare | Pet Shop and Veterinary Service</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>

<body class="home-page">

    <header class="top-bar">

        <div class="top-brand">
            <span class="brand-icon">🐾</span>
            <span>PawCare – Pet Shop and Veterinary Service Management System</span>
        </div>

        <div class="window-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>

    </header>

    <main class="home-container">

        <aside class="home-sidebar">

            <div class="sidebar-brand">
                <img src="assets/images/petLogo.jpeg" alt="PawCare Logo" class="sidebar-logo">

                <h2>
                    <span>🐾</span>
                    PAWCARE
                </h2>
            </div>

                <nav class="sidebar-menu">

                    <a href="<?php echo htmlspecialchars(route_url("login")); ?>" class="menu-btn">Sign In — All Roles</a>
                    <p class="login-hint">Admin · Customer · Doctor · Delivery</p>
                    <a href="<?php echo htmlspecialchars(route_url("pet_reviews")); ?>" class="menu-btn">Pet Reviews</a>
                    <a href="<?php echo htmlspecialchars(route_url("specialist_doctors")); ?>" class="menu-btn">Specialist Doctors</a>
                    <a href="<?php echo htmlspecialchars(route_url("delivery/index")); ?>" class="menu-btn">Delivery Man Portal</a>
                    <a href="#" class="menu-btn license-btn">Pet License</a>

                </nav>

        </aside>

        <section class="home-content">

            <div class="welcome-header">
                <span class="welcome-icon">🐾</span>

                <h1>
                    Welcome to Our Premium
                    <span>Pet Shop</span>
                </h1>
            </div>

            <div class="hero-area">
                <img src="assets/images/PetShopWallpaper.jpg" alt="PawCare Pet Shop" class="hero-image">
            </div>

        </section>

    </main>

    <footer class="home-footer">
        🐾 "Pets are not our whole life..." |
        PawCare © 2026
    </footer>

    <script src="assets/js/main.js"></script>

</body>

</html>
