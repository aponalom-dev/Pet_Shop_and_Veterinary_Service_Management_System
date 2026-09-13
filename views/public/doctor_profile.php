<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title><?php echo htmlspecialchars($doctor["full_name"]); ?> | PawCare</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/doctor-profile-public.css">
</head>

<body class="doctor-profile-page">

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

<main class="profile-container">

    <aside class="profile-sidebar">

        <div class="sidebar-brand">
            <img src="assets/images/petLogo.jpeg" alt="PawCare Logo" class="sidebar-logo">
            <h2>🐾 PAWCARE</h2>
        </div>

        <nav class="sidebar-menu">
            <a href="<?php echo htmlspecialchars(route_url("home")); ?>" class="menu-btn">Home</a>
            <a href="<?php echo htmlspecialchars(route_url("specialist_doctors")); ?>" class="menu-btn active">Specialist Doctors</a>
            <a href="<?php echo htmlspecialchars(route_url("login")); ?>" class="menu-btn doctor-login-btn">Sign In — All Roles</a>
        </nav>

    </aside>

    <section class="profile-content">

        <div class="profile-card">

            <div class="doctor-main-info">

                <div class="doctor-image-box">
                    <img src="assets/uploads/profiles/<?php echo htmlspecialchars(profile_image_file($profile_image)); ?>" onerror="this.onerror=null; this.src='assets/uploads/profiles/default.svg';" alt="<?php echo htmlspecialchars($doctor["full_name"]); ?>">

                    <span class="doctor-status <?php echo strtolower($doctor["status"]); ?>">
                        <?php echo htmlspecialchars($doctor["status"]); ?>
                    </span>
                </div>

                <div class="doctor-details">

                    <p class="section-label">Veterinary Specialist</p>

                    <h1><?php echo htmlspecialchars($doctor["full_name"]); ?></h1>

                    <h3><?php echo htmlspecialchars($doctor["specialization"]); ?></h3>

                    <p class="qualification">
                        <?php echo htmlspecialchars($doctor["qualification"]); ?>
                    </p>

                    <div class="stats-row">

                        <div class="stat-box">
                            <?php if ((int)$doctor["experience_years"] > 0): ?>
                                <span><?php echo (int)$doctor["experience_years"]; ?>+</span>
                                <p>Years Experience</p>
                            <?php else: ?>
                                <span>—</span>
                                <p>Experience pending</p>
                            <?php endif; ?>
                        </div>

                        <div class="stat-box">
                            <span><?php echo $completed_treatments; ?></span>
                            <p>Completed Treatments</p>
                        </div>

                        <div class="stat-box">
                            <span><?php echo number_format((float)$average_rating, 1); ?> ★</span>
                            <p><?php echo $total_reviews; ?> Reviews</p>
                        </div>

                    </div>

                    <div class="availability-box">

                        <p>
                            <strong>Available Days:</strong>
                            <?php echo htmlspecialchars($doctor["available_days"]); ?>
                        </p>

                        <p>
                            <strong>Available Time:</strong>
                            <?php echo htmlspecialchars($doctor["available_time"]); ?>
                        </p>

                        <p>
                            <strong>Consultation Fee:</strong>
                            <?php if ((float)$doctor["consultation_fee"] > 0): ?>
                                ৳<?php echo number_format((float)$doctor["consultation_fee"], 2); ?>
                            <?php else: ?>
                                To be confirmed
                            <?php endif; ?>
                        </p>

                    </div>

                    <?php if ($doctor["status"] === "Available"): ?>
                        <a href="<?php echo htmlspecialchars(route_url("book_appointment")); ?>&amp;doctor_id=<?php echo (int)$doctor["doctor_id"]; ?>" class="book-btn">
                            Book Appointment
                        </a>
                    <?php else: ?>
                        <span class="book-btn is-disabled">Booking unavailable</span>
                    <?php endif; ?>

                </div>

            </div>

            <div class="about-section">

                <h2>About Doctor</h2>

                <p>
                    <?php echo nl2br(htmlspecialchars($doctor["bio"])); ?>
                </p>

            </div>

            <div class="review-section">

                <div class="review-heading">
                    <div>
                        <h2>Customer Reviews</h2>
                        <p>Feedback from customers who visited this doctor.</p>
                    </div>

                    <div class="rating-summary">
                        <?php echo number_format((float)$average_rating, 1); ?> ★
                    </div>
                </div>

                <?php if (!empty($reviews)): ?>

                    <div class="review-list">

                        <?php foreach ($reviews as $review): ?>

                            <div class="review-card">

                                <div class="review-top">

                                    <div>
                                        <h4><?php echo htmlspecialchars($review["full_name"]); ?></h4>

                                        <span class="review-date">
                                            <?php echo date("d M Y", strtotime($review["created_at"])); ?>
                                        </span>
                                    </div>

                                    <div class="review-stars">
                                        <?php echo str_repeat("★", (int)$review["rating"]); ?>
                                    </div>

                                </div>

                                <p>
                                    <?php echo htmlspecialchars($review["comment"]); ?>
                                </p>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="no-reviews">
                        <p>No reviews available for this doctor yet.</p>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </section>

</main>

<footer class="profile-footer">
    🐾 "Care, compassion and trust for every pet." | PawCare Premium Pet Clinic © 2026
</footer>

</body>

</html>
