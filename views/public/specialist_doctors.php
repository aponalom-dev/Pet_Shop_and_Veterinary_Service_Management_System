<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>

    <title>Specialist Doctors | PawCare</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/specialist-doctors.css">
</head>

<body class="specialist-page">

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

<main class="doctor-container">

    <aside class="doctor-sidebar">

        <div class="sidebar-brand">

            <img src="assets/images/petLogo.jpeg" alt="PawCare Logo" class="sidebar-logo">

            <h2>🐾 PAWCARE</h2>

        </div>

        <nav class="sidebar-menu">

            <a href="<?php echo htmlspecialchars(route_url("home")); ?>" class="menu-btn">Home</a>

            <a href="<?php echo htmlspecialchars(route_url("login")); ?>" class="menu-btn">
                Sign In — All Roles
            </a>

            <a href="<?php echo htmlspecialchars(route_url("pet_reviews")); ?>" class="menu-btn">
                Pet Reviews
            </a>

            <a href="<?php echo htmlspecialchars(route_url("specialist_doctors")); ?>" class="menu-btn active">
                Specialist Doctors
            </a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/index")); ?>" class="menu-btn">
                Delivery Man Portal
            </a>

        </nav>

    </aside>

    <section class="doctor-content">

        <div class="doctor-heading">

            <span>🐾</span>

            <div>
                <h1>Our Specialist Doctors</h1>
                <p>Meet our trusted veterinary specialists and choose the right doctor for your pet.</p>
            </div>

        </div>

        <div class="doctor-grid">

            <?php if (!empty($doctors)): ?>

                <?php foreach ($doctors as $doctor): ?>

                    <?php

                    $profile_image = trim($doctor["profile_image"] ?? "");

                    if (empty($profile_image)) {
                        $profile_image = "default.png";
                    }

                    ?>

                    <article class="doctor-card">

                        <div class="doctor-photo">

                            <img
                                src="assets/uploads/profiles/<?php echo htmlspecialchars(profile_image_file($profile_image)); ?>"
                                onerror="this.onerror=null; this.src='assets/uploads/profiles/default.svg';"
                                alt="<?php echo htmlspecialchars($doctor["full_name"]); ?>"
                            >

                            <span class="availability <?php echo strtolower($doctor["status"]); ?>">
                                <?php echo htmlspecialchars($doctor["status"]); ?>
                            </span>

                        </div>

                        <div class="doctor-info">

                            <h2>
                                <?php echo htmlspecialchars($doctor["full_name"]); ?>
                            </h2>

                            <h4>
                                <?php echo htmlspecialchars($doctor["specialization"]); ?>
                            </h4>

                            <p class="qualification">
                                <?php echo htmlspecialchars($doctor["qualification"]); ?>
                                •
                                <?php if ((int)$doctor["experience_years"] > 0): ?>
                                    <?php echo (int)$doctor["experience_years"]; ?> Years Experience
                                <?php else: ?>
                                    Experience pending
                                <?php endif; ?>
                            </p>

                            <div class="doctor-details">

                                <p>
                                    <strong>Available</strong>
                                    <?php echo htmlspecialchars($doctor["available_days"]); ?>
                                </p>

                                <p>
                                    <strong>Time</strong>
                                    <?php echo htmlspecialchars($doctor["available_time"]); ?>
                                </p>

                                <p>
                                    <strong>Consultation Fee</strong>
                                    <?php if ((float)$doctor["consultation_fee"] > 0): ?>
                                        ৳<?php echo number_format((float)$doctor["consultation_fee"], 2); ?>
                                    <?php else: ?>
                                        To be confirmed
                                    <?php endif; ?>
                                </p>

                            </div>

                            <div class="doctor-actions">

                                <a
                                    href="<?php echo htmlspecialchars(route_url("doctor_profile")); ?>&amp;doctor_id=<?php echo (int)$doctor["doctor_id"]; ?>"
                                    class="profile-btn">
                                    View Profile
                                </a>

                                <?php if ($doctor["status"] === "Available"): ?>
                                    <a
                                        href="<?php echo htmlspecialchars(route_url("book_appointment")); ?>&amp;doctor_id=<?php echo (int)$doctor["doctor_id"]; ?>"
                                        class="appointment-btn">
                                        Book Appointment
                                    </a>
                                <?php else: ?>
                                    <span class="appointment-btn is-disabled">Booking unavailable</span>
                                <?php endif; ?>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="no-doctors">

                    <span>🐾</span>
                    <h2>No Doctors Available</h2>
                    <p>Doctor information will appear here.</p>

                </div>

            <?php endif; ?>

        </div>

    </section>

</main>

<footer class="doctor-footer">
    🐾 "Pets are not our whole life..." | PawCare Premium Pet Clinic © 2026
</footer>

</body>

</html>
