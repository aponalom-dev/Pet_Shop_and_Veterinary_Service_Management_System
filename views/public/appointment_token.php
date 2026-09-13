<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>

    <title>Appointment Token | PawCare</title>

    <link rel="stylesheet" href="assets/css/appointment-token.css">
</head>

<body>

<div class="token-page">

    <div class="token-card">

        <div class="token-header">
            <div class="paw-icon">🐾</div>
            <h1>PawCare</h1>
            <p>Pet Shop and Veterinary Service Management System</p>
        </div>

        <div class="success-message">
            ✓ Appointment Booked Successfully
        </div>

        <div class="token-number">
            <span>APPOINTMENT TOKEN</span>
            <strong>#<?php echo str_pad($appointment["appointment_id"], 4, "0", STR_PAD_LEFT); ?></strong>
        </div>

        <div class="token-info">

            <div class="info-row">
                <span>Doctor</span>
                <strong><?php echo htmlspecialchars($appointment["doctor_name"]); ?></strong>
            </div>

            <div class="info-row">
                <span>Specialization</span>
                <strong><?php echo htmlspecialchars($appointment["specialization"]); ?></strong>
            </div>

            <div class="info-row">
                <span>Guardian</span>
                <strong><?php echo htmlspecialchars($appointment["customer_name"]); ?></strong>
            </div>

            <div class="info-row">
                <span>Pet Name</span>
                <strong><?php echo htmlspecialchars($appointment["pet_name"]); ?></strong>
            </div>

            <div class="info-row">
                <span>Pet Type</span>
                <strong><?php echo htmlspecialchars($appointment["pet_type"]); ?></strong>
            </div>

            <div class="info-row">
                <span>Date</span>
                <strong><?php echo date("d M Y", strtotime($appointment["appointment_date"])); ?></strong>
            </div>

            <div class="info-row">
                <span>Time</span>
                <strong><?php echo date("h:i A", strtotime($appointment["appointment_time"])); ?></strong>
            </div>

            <div class="info-row">
                <span>Status</span>
                <strong class="status">
                    <?php echo htmlspecialchars($appointment["status"]); ?>
                </strong>
            </div>

        </div>

        <div class="reason-box">
            <span>Reason for Visit</span>
            <p><?php echo htmlspecialchars($appointment["reason"]); ?></p>
        </div>

        <div class="token-note">
            Please keep this token and arrive before your scheduled appointment time.
        </div>

        <div class="token-actions">
            <button type="button" onclick="window.print()">Print Token</button>
            <a href="<?php echo htmlspecialchars(route_url("specialist_doctors")); ?>">Close</a>
        </div>

    </div>

</div>

</body>

</html>
