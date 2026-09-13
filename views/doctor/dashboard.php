<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('doctor')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>

    <title>Doctor Dashboard | PawCare</title>

    <link rel="stylesheet" href="../assets/css/doctor-dashboard.css">
</head>

<body class="doctor-dashboard-page">

<header class="top-bar">

    <div class="top-brand">
        <span>🐾</span>
        <span>PawCare – Doctor Portal</span>
    </div>

    <div class="window-dots">
        <span></span>
        <span></span>
        <span></span>
    </div>

</header>

<aside class="doctor-sidebar">

    <div class="doctor-profile">

        <img src="../assets/uploads/profiles/<?php echo htmlspecialchars(profile_image_file($profile_image)); ?>"
             onerror="this.onerror=null; this.src='../assets/uploads/profiles/default.svg';"
             alt="<?php echo htmlspecialchars($doctor["full_name"]); ?>">

        <h2><?php echo htmlspecialchars($doctor["full_name"]); ?></h2>
        <p><?php echo htmlspecialchars($doctor["specialization"]); ?></p>

    </div>

<nav class="sidebar-menu">
    <a href="<?php echo htmlspecialchars(route_url("doctor/dashboard")); ?>" class="active">Dashboard</a>
    <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>">Appointments</a>
    <a href="<?php echo htmlspecialchars(route_url("doctor/medical_records")); ?>">Medical Records</a>
    <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">Logout</a>
</nav>

</aside>

<main class="dashboard-content">
    <div class="dashboard-heading">
        <div>
            <p>Doctor Portal</p>
            <h1>Welcome, Dr. <?php echo htmlspecialchars($doctor["full_name"]); ?></h1>
            <span>Manage your appointments and patient care from one place.</span>
        </div>
        <div class="doctor-status">
            <?php echo htmlspecialchars($doctor["status"]); ?>
        </div>

    </div>

    <section class="stats-grid">

        <div class="stat-card">
            <span>Total Appointments</span>
            <strong><?php echo $total_appointments; ?></strong>
        </div>

        <div class="stat-card">
            <span>Pending</span>
            <strong><?php echo $pending_appointments; ?></strong>
        </div>

        <div class="stat-card">
            <span>Today's Appointments</span>
            <strong><?php echo $today_appointments; ?></strong>
        </div>

        <div class="stat-card">
            <span>Completed Treatments</span>
            <strong><?php echo $completed_appointments; ?></strong>
        </div>

    </section>

    <section class="dashboard-grid">

        <div class="dashboard-card">

            <div class="card-heading">
                <h2>Upcoming Appointments</h2>
                <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>">View All</a>
            </div>

            <?php if (!empty($upcoming_appointments)): ?>

                <div class="appointment-list">

                    <?php foreach ($upcoming_appointments as $appointment): ?>

                        <div class="appointment-item">

                            <div class="appointment-main">

                                <h3><?php echo htmlspecialchars($appointment["pet_name"]); ?></h3>

                                <p>
                                    <?php echo htmlspecialchars($appointment["pet_type"]); ?>
                                    ·
                                    Guardian: <?php echo htmlspecialchars($appointment["customer_name"]); ?>
                                </p>

                            </div>

                            <div class="appointment-date">

                                <strong>
                                    <?php echo date("d M Y", strtotime($appointment["appointment_date"])); ?>
                                </strong>

                                <span>
                                    <?php echo date("h:i A", strtotime($appointment["appointment_time"])); ?>
                                </span>

                            </div>

                            <div class="appointment-status">
                                <?php echo htmlspecialchars($appointment["status"]); ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-message">
                    No upcoming appointments found.
                </div>

            <?php endif; ?>

        </div>

        <div class="dashboard-card doctor-info-card">

            <div class="card-heading">
                <h2>Doctor Information</h2>
            </div>

            <div class="doctor-info-row">
                <span>Qualification</span>
                <strong><?php echo htmlspecialchars($doctor["qualification"]); ?></strong>
            </div>

            <div class="doctor-info-row">
                <span>Experience</span>
                <strong><?php echo (int)$doctor["experience_years"]; ?> Years</strong>
            </div>

            <div class="doctor-info-row">
                <span>Consultation Fee</span>
                <strong>৳<?php echo number_format((float)$doctor["consultation_fee"], 2); ?></strong>
            </div>

            <div class="doctor-info-row">
                <span>Available Days</span>
                <strong><?php echo htmlspecialchars($doctor["available_days"]); ?></strong>
            </div>

            <div class="doctor-info-row">
                <span>Available Time</span>
                <strong><?php echo htmlspecialchars($doctor["available_time"]); ?></strong>
            </div>

            <div class="doctor-info-row">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($doctor["email"]); ?></strong>
            </div>

            <div class="doctor-info-row">
                <span>Phone</span>
                <strong><?php echo htmlspecialchars($doctor["phone"]); ?></strong>
            </div>

        </div>

    </section>

</main>

<footer class="doctor-footer">
    🐾 PawCare Doctor Portal © 2026
</footer>

</body>

</html>
