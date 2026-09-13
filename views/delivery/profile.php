<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('delivery')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Profile Settings | PawCare</title>
    <link rel="stylesheet" href="../assets/css/delivery_profile.css">
</head>

<body>

<div class="delivery-layout">

    <aside class="sidebar">

        <div class="sidebar-brand">

            <img src="../assets/images/petLogo.jpeg" alt="PawCare Logo">

            <h2>PAWCARE</h2>

            <p><?php echo htmlspecialchars($company_name); ?></p>

        </div>

        <nav class="sidebar-menu">

            <a href="<?php echo htmlspecialchars(route_url("delivery/dashboard")); ?>">Dashboard</a>
            <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>">Assigned Orders</a>
            <a href="<?php echo htmlspecialchars(route_url("delivery/history")); ?>">History</a>
            <a href="<?php echo htmlspecialchars(route_url("delivery/profile")); ?>" class="active">Profile Settings</a>

        </nav>

        <div class="sidebar-logout">
            <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">Logout</a>
        </div>

    </aside>

    <div class="main-area">

        <header class="topbar">

            <div>

                <h3>
                    WELCOME BACK, <?php echo strtoupper(htmlspecialchars($agent_name)); ?>!
                </h3>

                <p>Manage your delivery agent profile.</p>

            </div>

            <div class="topbar-time">

                <span id="currentDate"></span>
                <span id="currentTime"></span>

            </div>

        </header>

        <main class="content">

            <div class="page-heading">

                <div>

                    <h1>Profile Settings</h1>

                    <p>
                        View and update your personal information.
                    </p>

                </div>

                <div class="company-badge">
                    <?php echo htmlspecialchars($company_name); ?>
                </div>

            </div>

            <?php if ($message !== ""): ?>

                <div class="<?php echo $message_type === "success" ? "success-message" : "error-message"; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>

            <div class="profile-container">

                <section class="profile-card">

                    <div class="profile-avatar">

                        <div class="avatar-circle">
                            <?php echo strtoupper(substr($profile["full_name"], 0, 1)); ?>
                        </div>

                        <h2>
                            <?php echo htmlspecialchars($profile["full_name"]); ?>
                        </h2>

                        <p>
                            <?php echo htmlspecialchars($company_name); ?> Delivery Agent
                        </p>

                        <span class="active-badge">
                            <?php echo htmlspecialchars($profile["agent_status"]); ?>
                        </span>

                    </div>

                    <div class="profile-summary">

                        <div class="summary-row">

                            <span>Username</span>

                            <strong>
                                <?php echo htmlspecialchars($profile["username"]); ?>
                            </strong>

                        </div>

                        <div class="summary-row">

                            <span>Role</span>

                            <strong>
                                <?php echo ucfirst(htmlspecialchars($profile["role"])); ?>
                            </strong>

                        </div>

                        <div class="summary-row">

                            <span>Company</span>

                            <strong>
                                <?php echo htmlspecialchars($company_name); ?>
                            </strong>

                        </div>

                        <div class="summary-row">

                            <span>Account Status</span>

                            <strong>
                                <?php echo htmlspecialchars($profile["status"]); ?>
                            </strong>

                        </div>

                    </div>

                </section>

                <section class="details-card">

                    <div class="card-heading">

                        <div>

                            <h2>Personal Information</h2>

                            <p>Update your PawCare delivery profile.</p>

                        </div>

                        <span class="view-label">
                            EDIT PROFILE
                        </span>

                    </div>

                    <form method="POST" action="<?php echo htmlspecialchars(route_url("delivery/profile")); ?>">

                        <div class="details-grid">

                            <div class="detail-box">

                                <label>Full Name</label>

                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($profile["full_name"]); ?>" required>

                            </div>

                            <div class="detail-box">

                                <label>Username</label>

                                <input type="text" value="<?php echo htmlspecialchars($profile["username"]); ?>" disabled>

                            </div>

                            <div class="detail-box">

                                <label>Email Address</label>

                                <input type="email" value="<?php echo htmlspecialchars($profile["email"]); ?>" disabled>

                            </div>

                            <div class="detail-box">

                                <label>Phone Number</label>

                                <input type="text" name="phone" value="<?php echo htmlspecialchars($profile["phone"] ?? ""); ?>" required>

                            </div>

                            <div class="detail-box">

                                <label>Gender</label>

                                <input type="text" value="<?php echo htmlspecialchars($profile["gender"] ?: "Not provided"); ?>" disabled>

                            </div>

                            <div class="detail-box">

                                <label>Delivery Company</label>

                                <input type="text" value="<?php echo htmlspecialchars($company_name); ?>" disabled>

                            </div>

                            <div class="detail-box full-width">

                                <label>Address</label>

                                <textarea name="address" rows="4" required><?php echo htmlspecialchars($profile["address"] ?? ""); ?></textarea>

                            </div>

                        </div>

                        <div class="profile-actions">

                            <button type="submit" name="save_profile" class="save-btn">
                                SAVE CHANGES
                            </button>

                            <a href="<?php echo htmlspecialchars(route_url("delivery/profile")); ?>" class="cancel-btn">
                                CANCEL
                            </a>

                        </div>

                    </form>

                    <div class="profile-note">

                        <strong>Account Information</strong>

                        <p>
                            Username, email, gender, role and delivery company cannot be changed from this page.
                        </p>

                    </div>

                </section>

            </div>

        </main>

    </div>

</div>

<footer class="delivery-footer">
    POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
</footer>

<script>

function updateDateTime() {
    const now = new Date();

    document.getElementById("currentDate").textContent = now.toLocaleDateString("en-US", {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric"
    });

    document.getElementById("currentTime").textContent = now.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit"
    });
}

updateDateTime();
setInterval(updateDateTime, 1000);

</script>

</body>

</html>
