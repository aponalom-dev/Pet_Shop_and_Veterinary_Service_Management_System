<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Change Password | PawCare</title>
    <link rel="stylesheet" href="../assets/css/change-password.css">
</head>

<body>

<div class="password-page">

    <header class="password-header">
        <div>
            <h1>CHANGE PASSWORD</h1>
            <p>Update your PawCare account password</p>
        </div>

        <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>">← BACK TO PROFILE</a>
    </header>

    <main class="password-container">
        <section class="password-card">

            <div class="password-user">
                <div class="password-avatar">
                    <?php echo strtoupper(substr($customer["username"], 0, 1)); ?>
                </div>

                <div>
                    <h2><?php echo htmlspecialchars($customer["full_name"]); ?></h2>
                    <p>@<?php echo htmlspecialchars($customer["username"]); ?></p>
                </div>
            </div>

            <?php if ($error !== ""): ?>
                <div class="password-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="password-form">

                <div class="password-group">
                    <label>CURRENT PASSWORD</label>
                    <input type="password" name="current_password" placeholder="Enter current password" required>
                </div>

                <div class="password-group">
                    <label>NEW PASSWORD</label>
                    <input type="password" name="new_password" placeholder="Enter new password" minlength="8" required>
                    <small>Minimum 8 characters.</small>
                </div>

                <div class="password-group">
                    <label>CONFIRM NEW PASSWORD</label>
                    <input type="password" name="confirm_password" placeholder="Enter new password again" minlength="8" required>
                </div>

                <div class="password-actions">
                    <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>">CANCEL</a>
                    <button type="submit">UPDATE PASSWORD</button>
                </div>

            </form>

        </section>
    </main>

</div>

</body>
</html>
