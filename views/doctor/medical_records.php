<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('doctor')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>

    <title>Medical Records | PawCare Doctor</title>

    <link rel="stylesheet" href="../assets/css/doctor-medical-records.css">
</head>

<body class="medical-records-page">

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
        <a href="<?php echo htmlspecialchars(route_url("doctor/dashboard")); ?>">Dashboard</a>
        <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>">Appointments</a>
        <a href="<?php echo htmlspecialchars(route_url("doctor/medical_records")); ?>" class="active">Medical Records</a>
        <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">Logout</a>
    </nav>

</aside>

<main class="records-content">

    <div class="page-heading">

        <div>
            <p>Doctor Portal</p>
            <h1>Medical Records</h1>
            <span>View previous patient diagnosis, prescriptions and treatment history.</span>
        </div>

        <div class="record-count">
            <?php echo count($medical_records); ?> Records
        </div>

    </div>

    <?php if (!empty($medical_records)): ?>

        <section class="records-grid">

            <?php foreach ($medical_records as $record): ?>

                <article class="record-card">

                    <div class="record-header">

                        <div>
                            <span class="record-label">Medical Record</span>

                            <h2>
                                #<?php echo str_pad($record["record_id"], 4, "0", STR_PAD_LEFT); ?>
                            </h2>
                        </div>

                        <div class="appointment-token">
                            Appointment
                            #<?php echo str_pad($record["appointment_id"], 4, "0", STR_PAD_LEFT); ?>
                        </div>

                    </div>

                    <div class="patient-info">

                        <div>
                            <span>Guardian</span>
                            <strong><?php echo htmlspecialchars($record["customer_name"]); ?></strong>
                            <small><?php echo htmlspecialchars($record["customer_phone"]); ?></small>
                        </div>

                        <div>
                            <span>Pet</span>
                            <strong><?php echo htmlspecialchars($record["pet_name"]); ?></strong>
                            <small><?php echo htmlspecialchars($record["pet_type"]); ?></small>
                        </div>

                        <div>
                            <span>Appointment</span>

                            <strong>
                                <?php echo date("d M Y", strtotime($record["appointment_date"])); ?>
                            </strong>

                            <small>
                                <?php echo date("h:i A", strtotime($record["appointment_time"])); ?>
                            </small>
                        </div>

                    </div>

                    <div class="record-section">
                        <span>Diagnosis</span>

                        <p>
                            <?php echo nl2br(htmlspecialchars($record["diagnosis"])); ?>
                        </p>
                    </div>

                    <div class="record-section">
                        <span>Prescription</span>

                        <p>
                            <?php echo nl2br(htmlspecialchars($record["prescription"])); ?>
                        </p>
                    </div>

                    <div class="record-section">
                        <span>Treatment Notes</span>

                        <p>
                            <?php
                            if (!empty($record["treatment_notes"])) {
                                echo nl2br(htmlspecialchars($record["treatment_notes"]));
                            } else {
                                echo "No treatment notes available.";
                            }
                            ?>
                        </p>
                    </div>

                    <div class="record-footer">

                        <div>
                            <span>Next Visit</span>

                            <strong>
                                <?php
                                if (!empty($record["next_visit_date"])) {
                                    echo date("d M Y", strtotime($record["next_visit_date"]));
                                } else {
                                    echo "Not Scheduled";
                                }
                                ?>
                            </strong>
                        </div>

                        <div>
                            <span>Record Created</span>

                            <strong>
                                <?php echo date("d M Y", strtotime($record["created_at"])); ?>
                            </strong>
                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

    <?php else: ?>

        <section class="empty-records">

            <div class="empty-icon">📋</div>

            <h2>No Medical Records Found</h2>

            <p>
                There are currently no medical records available for your patients.
            </p>

            <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>">View Appointments</a>

        </section>

    <?php endif; ?>

</main>

<footer class="doctor-footer">
    🐾 PawCare Doctor Portal © 2026
</footer>

</body>

</html>
