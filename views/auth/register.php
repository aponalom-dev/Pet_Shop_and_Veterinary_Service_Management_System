<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Create Account | PawCare</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">
</head>

<body class="auth-page">

    <header class="top-bar">
        <span class="brand-icon">🐾</span>
        <span>PawCare – Pet Shop and Veterinary Service Management System</span>

        <div class="window-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <main class="registration-container">

        <section class="registration-card">

            <div class="registration-brand">

                <div class="registration-logo">
                    <img src="assets/images/petLogo.jpeg" alt="PawCare Logo">
                </div>

                <h3><span>🐾</span> PAWCARE</h3>

            </div>

            <div class="registration-heading">
                <h1>Join the Pet Family</h1>
                <p>Create your account to access premium pet care</p>
            </div>

            <?php if (!empty($error_message)): ?>

                <div class="message error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>

            <?php endif; ?>

            <?php if (!empty($success_message)): ?>

                <div class="message success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>

            <?php endif; ?>

            <form action="" method="POST" id="registrationForm">
                <?php csrf_field(); ?>

                <div class="form-group account-role">
                    <label for="role">Account type</label>
                    <select id="role" name="role" required>
                        <option value="customer" <?php if ($account["role"] === "customer") echo "selected"; ?>>Customer</option>
                        <option value="doctor" <?php if ($account["role"] === "doctor") echo "selected"; ?>>Doctor</option>
                        <option value="delivery" <?php if ($account["role"] === "delivery") echo "selected"; ?>>Delivery</option>
                    </select>
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($account["full_name"]); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Choose Username</label>
                        <input type="text" id="username" name="username" maxlength="50" data-check-url="<?php echo htmlspecialchars(route_url("ajax", "action=check_username")); ?>" placeholder="Choose a username" value="<?php echo htmlspecialchars($account["username"]); ?>" required>
                        <small id="usernameNote" class="field-note" aria-live="polite"></small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="example@email.com" value="<?php echo htmlspecialchars($account["email"]); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="01XXXXXXXXX" value="<?php echo htmlspecialchars($account["phone"]); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Set Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Repeat Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter password again" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Home Address</label>
                        <input type="text" id="address" name="address" placeholder="Please enter exact delivery address" value="<?php echo htmlspecialchars($account["address"]); ?>" <?php if ($account["role"] === "customer") echo "required"; ?>>
                    </div>

                </div>

                <div class="role-details" id="doctorFields" <?php if ($account["role"] !== "doctor") echo "hidden"; ?>>
                    <h2>Doctor details</h2>
                    <div class="form-grid">
                        <div class="form-group"><label for="specialization">Specialization</label><input type="text" id="specialization" name="specialization" value="<?php echo htmlspecialchars($account["specialization"]); ?>" placeholder="Small Animal Medicine"></div>
                        <div class="form-group"><label for="qualification">Qualification</label><input type="text" id="qualification" name="qualification" value="<?php echo htmlspecialchars($account["qualification"]); ?>" placeholder="DVM"></div>
                        <div class="form-group"><label for="experience_years">Experience (years)</label><input type="number" id="experience_years" name="experience_years" min="0" max="99" value="<?php echo htmlspecialchars($account["experience_years"]); ?>"></div>
                        <div class="form-group"><label for="consultation_fee">Consultation fee (BDT)</label><input type="number" id="consultation_fee" name="consultation_fee" min="0" step="0.01" value="<?php echo htmlspecialchars($account["consultation_fee"]); ?>"></div>
                        <div class="form-group"><label for="available_days">Available days</label><input type="text" id="available_days" name="available_days" value="<?php echo htmlspecialchars($account["available_days"]); ?>" placeholder="Sunday, Tuesday"></div>
                        <div class="form-group"><label for="available_time">Available time</label><input type="text" id="available_time" name="available_time" value="<?php echo htmlspecialchars($account["available_time"]); ?>" placeholder="10:00 AM - 4:00 PM"></div>
                        <div class="form-group wide-field"><label for="bio">Short bio</label><textarea id="bio" name="bio" rows="3" placeholder="Your veterinary experience"><?php echo htmlspecialchars($account["bio"]); ?></textarea></div>
                    </div>
                </div>

                <div class="role-details" id="deliveryFields" <?php if ($account["role"] !== "delivery") echo "hidden"; ?>>
                    <h2>Delivery details</h2>
                    <div class="form-group">
                        <label for="company_name">Delivery company</label>
                        <select id="company_name" name="company_name">
                            <option value="">Choose a company</option>
                            <?php foreach (["Pathao Fast", "PetPanda Go", "Speed Fast", "Jhinku BD"] as $company): ?>
                                <option value="<?php echo htmlspecialchars($company); ?>" <?php if ($account["company_name"] === $company) echo "selected"; ?>><?php echo htmlspecialchars($company); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="create-account-btn">Create Account</button>

            </form>

            <p class="login-link">
                Already have an account?
                <a href="<?php echo htmlspecialchars(route_url("login")); ?>">Login here</a>
            </p>

        </section>

    </main>

    <footer class="footer">
        🐾 "Pets are not our whole life..." | PawCare © 2026
    </footer>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/register.js"></script>

</body>

</html>
