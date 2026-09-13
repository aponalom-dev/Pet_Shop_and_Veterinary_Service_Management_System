<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Login | PawCare</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="auth-page">

<?php if (isset($_GET["logout"]) && $_GET["logout"] === "1"): ?>

<script>
    alert("You have been logged out successfully!");
</script>

<?php endif; ?>

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

    <main class="login-container">

        <section class="login-card">

            <div class="login-brand">
                <img src="assets/images/petLogo.jpeg" alt="PawCare Logo" class="login-logo">

                <h3>
                    <span>🐾</span>
                    PAWCARE
                </h3>
            </div>

            <div class="login-heading">
                <h1>Welcome Back</h1>
                <p>One sign-in for customers, doctors, delivery agents and admins. We’ll open your dashboard automatically.</p>
            </div>

            <?php if (!empty($error_message)): ?>

                <div class="login-message error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>

            <?php endif; ?>

            <form action="" method="POST" id="loginForm">
                <?php csrf_field(); ?>

                <div class="form-group">
                    <label for="login_identifier">Username or Email</label>

                    <input
                        type="text"
                        id="login_identifier"
                        name="login_identifier"
                        placeholder="Enter username or email"
                        value="<?php echo htmlspecialchars($login_identifier); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter password" required>

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                            aria-label="Show password">
                            👁
                        </button>
                    </div>
                </div>

                <div class="login-options">

                    <label class="remember-option">
                        <input
                            type="checkbox"
                            name="remember_me"
                            id="remember_me"
                            <?php
                            if (isset($_COOKIE["remember_login"])) {
                                echo "checked";
                            }
                            ?>>

                        <span>Remember me</span>
                    </label>

                    <a href="#" class="forgot-password">Forgot Password?</a>

                </div>

                <button type="submit" class="login-btn">Login</button>

            </form>

            <p class="signup-link">
                Don't have an account?
                <a href="<?php echo htmlspecialchars(route_url("register")); ?>">Sign Up</a>
            </p>

        </section>

    </main>

    <footer class="footer">
        🐾 "Pets are not our whole life..." |
        PawCare © 2026
    </footer>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/login.js"></script>

</body>

</html>
