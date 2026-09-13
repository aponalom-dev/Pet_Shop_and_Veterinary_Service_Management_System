<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Edit Profile | PawCare</title>
    <link rel="stylesheet" href="../assets/css/edit-profile.css">
</head>

<body>

<div class="edit-page">

    <header class="edit-header">
        <div>
            <h1>EDIT PROFILE</h1>
            <p>Update your PawCare information</p>
        </div>

        <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>">← BACK TO PROFILE</a>
    </header>

    <main class="edit-container">

        <section class="edit-card">

            <div class="edit-card-heading">

                <div class="edit-avatar">
                    <?php echo strtoupper(substr($customer["username"], 0, 1)); ?>
                </div>

                <div>
                    <h2><?php echo htmlspecialchars($customer["full_name"]); ?></h2>
                    <p>@<?php echo htmlspecialchars($customer["username"]); ?></p>
                </div>

            </div>

            <?php if ($error !== ""): ?>

                <div class="edit-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>

            <form method="POST" class="edit-form">

                <div class="form-group">
                    <label>FULL NAME</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($customer["full_name"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>USERNAME</label>
                    <input type="text" value="<?php echo htmlspecialchars($customer["username"]); ?>" disabled>
                    <small>Username cannot be changed.</small>
                </div>

                <div class="form-group">
                    <label>EMAIL ADDRESS</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($customer["email"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>PHONE NUMBER</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($customer["phone"] ?? ""); ?>" required>
                </div>

                <div class="form-group full-width">
                    <label>SHIPPING ADDRESS</label>
                    <textarea name="address" rows="4" required><?php echo htmlspecialchars($customer["address"] ?? ""); ?></textarea>
                </div>

                <div class="edit-actions">
                    <a href="<?php echo htmlspecialchars(route_url("customer/profile")); ?>">CANCEL</a>
                    <button type="submit">SAVE CHANGES</button>
                </div>

            </form>

        </section>

    </main>

</div>

</body>

</html>
