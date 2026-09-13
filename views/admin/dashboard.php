<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo htmlspecialchars(role_base_url('admin')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Admin Accounts - PawCare</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>

<div class="admin-page">

    <header class="top-header">
        <div>
            <h1>WELCOME BACK,<br>MASTER!</h1>
            <p>👑 Your PawCare Kingdom is under your command!</p>
        </div>

        <div class="header-time">
            <span id="currentDate"></span>
            <span id="currentTime"></span>
        </div>
    </header>

    <div class="admin-layout">

        <aside class="sidebar">
            <div class="brand-area">
                <div class="brand-logo">🐾</div>
                <h2>PAWCARE</h2>
            </div>

            <nav class="sidebar-menu">
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>" class="active">Manage Accounts</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/dashboard_overview")); ?>">▣ Dashboard Overview</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/inventory")); ?>">▤ Inventory Stock</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/sales_analytics")); ?>">💰 Sales Analytics</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/delivery_tracking")); ?>">🚚 Delivery Tracking</a>
                <a href="<?php echo htmlspecialchars(route_url("admin/customer_reviews")); ?>">★ Customer Reviews</a>
            </nav>

            <div class="sidebar-user">
                <p><?= htmlspecialchars($admin_name) ?></p>
                <span>@<?= htmlspecialchars($username) ?></span>
                <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="account-page-heading">
                <span>PAWCARE / ADMINISTRATION</span>
                <h2 class="overview-title">Account Management</h2>
                <p>Keep your care team, customers and delivery crew in one place.</p>
            </div>

            <section class="account-form-card" id="account-form">
                <h3><?php echo $editing_id > 0 ? "Edit account" : "Add a new account"; ?></h3>
                <?php if ($error_message !== ""): ?>
                    <p class="account-message account-error"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
                <?php if ($success_message !== ""): ?>
                    <p class="account-message account-success"><?php echo htmlspecialchars($success_message); ?></p>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>" id="registrationForm" data-editing="<?php echo $editing_id > 0 ? "1" : "0"; ?>">
                    <?php csrf_field(); ?>
                    <?php if ($editing_id > 0): ?>
                        <input type="hidden" name="account_id" value="<?php echo $editing_id; ?>">
                    <?php endif; ?>
                    <div class="account-grid">
                        <div class="account-field"><label for="full_name">Full name</label><input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($account["full_name"]); ?>" placeholder="Full name" required></div>
                        <div class="account-field"><label for="email">Email</label><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($account["email"]); ?>" placeholder="name@example.com" required></div>
                        <div class="account-field"><label for="phone">Contact number</label><input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($account["phone"]); ?>" placeholder="01XXXXXXXXX" required></div>
                        <div class="account-field"><label for="username">Username</label><input type="text" id="username" name="username" maxlength="50" data-check-url="<?php echo htmlspecialchars(route_url("ajax", "action=check_username")); ?>" data-current-username="<?php echo $editing_id > 0 ? htmlspecialchars($account["username"]) : ""; ?>" value="<?php echo htmlspecialchars($account["username"]); ?>" placeholder="Login username" required><small id="usernameNote" class="field-note" aria-live="polite"></small></div>
                        <div class="account-field"><label for="role">Role</label><select id="role" name="role" required>
                            <?php foreach (["customer", "doctor", "delivery", "admin"] as $role): ?>
                                <option value="<?php echo $role; ?>" <?php if ($account["role"] === $role) echo "selected"; ?>><?php echo ucfirst($role); ?></option>
                            <?php endforeach; ?>
                        </select></div>
                        <div class="account-field"><label for="password"><?php echo $editing_id > 0 ? "New password (optional)" : "Password"; ?></label><input type="password" id="password" name="password" placeholder="<?php echo $editing_id > 0 ? "Leave blank to keep current password" : "At least 6 characters"; ?>" <?php if ($editing_id === 0) echo "required"; ?>></div>
                        <div class="account-field account-wide"><label for="address">Address</label><input type="text" id="address" name="address" value="<?php echo htmlspecialchars($account["address"]); ?>" placeholder="Required for customer orders"></div>
                        <?php if ($editing_id > 0): ?>
                            <div class="account-field"><label for="status">Status</label><select id="status" name="status"><option value="active" <?php if ($account["status"] === "active") echo "selected"; ?>>Active</option><option value="inactive" <?php if ($account["status"] === "inactive") echo "selected"; ?>>Suspended</option></select></div>
                        <?php endif; ?>
                    </div>

                    <div class="account-role-details" id="doctorFields" <?php if ($account["role"] !== "doctor") echo "hidden"; ?>>
                        <h4>Doctor details</h4>
                        <div class="account-grid">
                            <div class="account-field"><label for="specialization">Specialization</label><input type="text" id="specialization" name="specialization" value="<?php echo htmlspecialchars($account["specialization"]); ?>" placeholder="Small Animal Medicine"></div>
                            <div class="account-field"><label for="qualification">Qualification</label><input type="text" id="qualification" name="qualification" value="<?php echo htmlspecialchars($account["qualification"]); ?>" placeholder="DVM"></div>
                            <div class="account-field"><label for="experience_years">Experience (years)</label><input type="number" id="experience_years" name="experience_years" min="0" max="99" value="<?php echo htmlspecialchars($account["experience_years"]); ?>"></div>
                            <div class="account-field"><label for="consultation_fee">Consultation fee (BDT)</label><input type="number" id="consultation_fee" name="consultation_fee" min="0" step="0.01" value="<?php echo htmlspecialchars($account["consultation_fee"]); ?>"></div>
                            <div class="account-field"><label for="available_days">Available days</label><input type="text" id="available_days" name="available_days" value="<?php echo htmlspecialchars($account["available_days"]); ?>" placeholder="Sunday, Tuesday"></div>
                            <div class="account-field"><label for="available_time">Available time</label><input type="text" id="available_time" name="available_time" value="<?php echo htmlspecialchars($account["available_time"]); ?>" placeholder="10:00 AM - 4:00 PM"></div>
                            <div class="account-field account-wide"><label for="bio">Short bio</label><textarea id="bio" name="bio" rows="3"><?php echo htmlspecialchars($account["bio"]); ?></textarea></div>
                            <?php if ($editing_id > 0): ?>
                                <div class="account-field account-wide doctor-photo-field">
                                    <label for="profile_image">Doctor photo</label>
                                    <div class="doctor-photo-picker">
                                        <img id="doctorPhotoPreview" src="../assets/uploads/profiles/<?php echo htmlspecialchars(profile_image_file($account["profile_image"])); ?>" alt="Selected doctor photo preview">
                                        <select id="profile_image" name="profile_image">
                                            <option value="default.png" <?php if ($account["profile_image"] === "default.png") echo "selected"; ?>>Default avatar</option>
                                            <?php foreach ($photo_choices as $photo): ?>
                                                <option value="<?php echo htmlspecialchars($photo); ?>" <?php if ($account["profile_image"] === $photo) echo "selected"; ?>><?php echo htmlspecialchars($photo); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <small>Choose a portrait only when you know it belongs to this doctor.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="account-role-details" id="deliveryFields" <?php if ($account["role"] !== "delivery") echo "hidden"; ?>>
                        <h4>Delivery details</h4>
                        <div class="account-field"><label for="company_name">Delivery company</label><select id="company_name" name="company_name">
                            <option value="">Choose a company</option>
                            <?php foreach (["Pathao Fast", "PetPanda Go", "Speed Fast", "Jhinku BD"] as $company): ?>
                                <option value="<?php echo htmlspecialchars($company); ?>" <?php if ($account["company_name"] === $company) echo "selected"; ?>><?php echo htmlspecialchars($company); ?></option>
                            <?php endforeach; ?>
                        </select></div>
                    </div>

                    <div class="account-actions">
                        <?php if ($editing_id > 0): ?><a class="account-cancel" href="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>">Cancel</a><?php endif; ?>
                        <button type="submit" name="<?php echo $editing_id > 0 ? "save_account" : "create_account"; ?>" value="1"><?php echo $editing_id > 0 ? "Save changes" : "Create account"; ?></button>
                    </div>
                </form>
            </section>

            <section class="account-list-card" id="account-list" data-search-url="<?php echo htmlspecialchars(route_url("ajax", "action=search_accounts")); ?>" data-action-url="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>" data-csrf-token="<?php echo htmlspecialchars(csrf_token()); ?>" data-self-id="<?php echo (int)$_SESSION["user_id"]; ?>">
                <div class="account-toolbar">
                    <label class="account-search"><span aria-hidden="true">🔍</span><input type="search" id="accountSearch" placeholder="Search by name, username, email or phone..." aria-label="Search accounts"></label>
                    <select id="accountRoleFilter" aria-label="Filter accounts by role"><option value="">All roles</option><option value="admin">Administrators</option><option value="customer">Customers</option><option value="doctor">Doctors</option><option value="delivery">Delivery</option></select>
                    <span class="account-count" id="accountCount"><?php echo count($accounts); ?> accounts</span>
                </div>
                <div class="account-table-wrap">
                    <table class="account-table">
                        <thead><tr><th>#</th><th>Name</th><th>Username</th><th>Email</th><th>Contact</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody id="accountTable">
                            <?php foreach ($accounts as $index => $user): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($user["full_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($user["username"]); ?></td>
                                    <td><?php echo htmlspecialchars($user["email"]); ?></td>
                                    <td><?php echo htmlspecialchars($user["phone"]); ?></td>
                                    <td><span class="account-pill role-<?php echo htmlspecialchars($user["role"]); ?>"><?php echo htmlspecialchars(ucfirst($user["role"])); ?></span></td>
                                    <td><span class="account-pill status-<?php echo htmlspecialchars($user["status"]); ?>"><?php echo $user["status"] === "active" ? "Active" : "Suspended"; ?></span></td>
                                    <td><div class="account-row-actions">
                                        <a class="account-action edit" href="<?php echo htmlspecialchars(route_url("admin/dashboard", "edit=" . (int)$user["user_id"])); ?>">Edit</a>
                                        <?php if ((int)$user["user_id"] !== (int)$_SESSION["user_id"]): ?>
                                            <form method="POST" action="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>" data-confirm="<?php echo $user["status"] === "active" ? "Suspend this account?" : "Reactivate this account?"; ?>"><?php csrf_field(); ?><input type="hidden" name="account_id" value="<?php echo (int)$user["user_id"]; ?>"><button type="submit" name="set_status" value="1" class="account-action <?php echo $user["status"] === "active" ? "suspend" : "activate"; ?>"><?php echo $user["status"] === "active" ? "Suspend" : "Activate"; ?></button></form>
                                            <form method="POST" action="<?php echo htmlspecialchars(route_url("admin/dashboard")); ?>" data-confirm="Delete this account permanently? Related records may also be removed."><?php csrf_field(); ?><input type="hidden" name="account_id" value="<?php echo (int)$user["user_id"]; ?>"><button type="submit" name="delete_account" value="1" class="account-action delete">Delete</button></form>
                                        <?php else: ?><span class="account-self">Current account</span><?php endif; ?>
                                    </div></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

    </div>

    <footer class="admin-footer">
        🛡 POWERFUL PET MANAGEMENT ENGINE V2.0 &nbsp; | &nbsp; SYSTEM SECURED
    </footer>

</div>

<script src="../assets/js/admin-dashboard.js"></script>
<script src="../assets/js/register.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/admin-accounts.js"></script>

</body>
</html>
