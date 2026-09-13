<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('delivery')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Delivery Man Portal - PawCare</title>
    <link rel="stylesheet" href="../assets/css/delivery_portal.css">
</head>
<body>

<div class="delivery-page">

    <div class="portal-header">
        <h1>Delivery Man Portal</h1>
        <p>Find your delivery team, then sign in with your account</p>
    </div>

    <div class="company-container">

        <?php foreach ($company_data as $company): ?>

            <div class="company-card">
                <div class="company-icon">🚚</div>

                <h2><?php echo htmlspecialchars($company["name"]); ?></h2>

                <p class="agent-name">
                    Agent: <?php echo htmlspecialchars($company["agent"]); ?>
                </p>

                <div class="order-count">
                    <span><?php echo $company["pending"]; ?></span>
                    <p>Pending Orders</p>
                </div>

                <a href="<?php echo htmlspecialchars(route_url("login")); ?>" class="login-btn">
                    Sign In
                </a>
            </div>

        <?php endforeach; ?>

    </div>

    <div class="back-home">
        <a href="<?php echo htmlspecialchars(route_url("home")); ?>">← Back to Home</a>
    </div>

</div>

</body>
</html>
