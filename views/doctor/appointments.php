<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('doctor')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>

    <title>Appointments | PawCare Doctor</title>

    <link rel="stylesheet" href="../assets/css/doctor-appointments.css">
</head>

<body class="doctor-appointments-page">

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
    <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>" class="active">Appointments</a>
    <a href="<?php echo htmlspecialchars(route_url("doctor/medical_records")); ?>">Medical Records</a>
    <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">Logout</a>
</nav>

</aside>

<main class="appointments-content">

    <div class="page-heading">

        <div>
            <p>Doctor Portal</p>
            <h1>Appointment Management</h1>
            <span>View and manage your patient appointments.</span>
        </div>

        <div class="appointment-count">
            <?php echo count($appointments); ?> Appointments
        </div>

    </div>

    <?php if (!empty($success_message)): ?>
        <div class="message success-message">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="message error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="filter-menu">

        <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>&amp;filter=all"
           class="<?php echo $filter === "all" ? "active" : ""; ?>">
            All
        </a>

        <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>&amp;filter=pending"
           class="<?php echo $filter === "pending" ? "active" : ""; ?>">
            Pending
        </a>

        <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>&amp;filter=confirmed"
           class="<?php echo $filter === "confirmed" ? "active" : ""; ?>">
            Confirmed
        </a>

        <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>&amp;filter=completed"
           class="<?php echo $filter === "completed" ? "active" : ""; ?>">
            Completed
        </a>

        <a href="<?php echo htmlspecialchars(route_url("doctor/appointments")); ?>&amp;filter=cancelled"
           class="<?php echo $filter === "cancelled" ? "active" : ""; ?>">
            Cancelled
        </a>

    </div>

    <section class="appointments-card">

        <?php if (!empty($appointments)): ?>

            <div class="table-wrapper">

                <table class="appointments-table">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guardian</th>
                        <th>Pet</th>
                        <th>Schedule</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($appointments as $appointment): ?>

                        <tr>

                            <td>
                                #<?php echo str_pad($appointment["appointment_id"], 4, "0", STR_PAD_LEFT); ?>
                            </td>

                            <td>
                                <strong><?php echo htmlspecialchars($appointment["customer_name"]); ?></strong>
                                <span><?php echo htmlspecialchars($appointment["customer_phone"]); ?></span>
                            </td>

                            <td>
                                <strong><?php echo htmlspecialchars($appointment["pet_name"]); ?></strong>
                                <span><?php echo htmlspecialchars($appointment["pet_type"]); ?></span>
                            </td>

                            <td>
                                <strong>
                                    <?php echo date("d M Y", strtotime($appointment["appointment_date"])); ?>
                                </strong>

                                <span>
                                    <?php echo date("h:i A", strtotime($appointment["appointment_time"])); ?>
                                </span>
                            </td>

                            <td class="reason-cell">
                                <?php echo htmlspecialchars($appointment["reason"]); ?>
                            </td>

                            <td>
                                <span class="status status-<?php echo strtolower($appointment["status"]); ?>">
                                    <?php echo htmlspecialchars($appointment["status"]); ?>
                                </span>
                            </td>

                            <td>

                                <?php if ($appointment["status"] === "Pending"): ?>

                                    <div class="action-buttons">

                                        <form method="POST">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["appointment_id"]; ?>">
                                            <input type="hidden" name="status" value="Confirmed">

                                            <button type="submit" class="confirm-btn">
                                                Confirm
                                            </button>
                                        </form>

                                        <form method="POST">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["appointment_id"]; ?>">
                                            <input type="hidden" name="status" value="Cancelled">

                                            <button type="submit" class="cancel-btn">
                                                Cancel
                                            </button>
                                        </form>

                                    </div>

                                <?php elseif ($appointment["status"] === "Confirmed"): ?>

                                    <div class="action-buttons">

                                        <form method="POST">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["appointment_id"]; ?>">
                                            <input type="hidden" name="status" value="Completed">

                                            <button type="submit" class="complete-btn">
                                                Complete
                                            </button>
                                        </form>

                                        <form method="POST">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["appointment_id"]; ?>">
                                            <input type="hidden" name="status" value="Cancelled">

                                            <button type="submit" class="cancel-btn">
                                                Cancel
                                            </button>
                                        </form>

                                    </div>

                                <?php else: ?>

                                    <span class="no-action">
                                        No Action
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty-appointments">

                <h3>No appointments found</h3>

                <p>
                    There are no appointments available under this category.
                </p>

            </div>

        <?php endif; ?>

    </section>

</main>

<footer class="doctor-footer">
    🐾 PawCare Doctor Portal © 2026
</footer>

</body>

</html>
