<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../partials/meta.php'; ?>

    <title>Book Appointment | PawCare</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/book-appointment.css">
</head>

<body class="appointment-page">

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

<main class="appointment-container">

    <aside class="appointment-sidebar">

        <div class="sidebar-brand">

            <img src="assets/images/petLogo.jpeg" alt="PawCare Logo" class="sidebar-logo">

            <h2>🐾 PAWCARE</h2>

        </div>

        <nav class="sidebar-menu">

            <a href="<?php echo htmlspecialchars(route_url("home")); ?>" class="menu-btn">Home</a>

            <a href="<?php echo htmlspecialchars(route_url("specialist_doctors")); ?>" class="menu-btn">
                Specialist Doctors
            </a>

            <a href="<?php echo htmlspecialchars(route_url("doctor_profile")); ?>&amp;doctor_id=<?php echo (int)$doctor["doctor_id"]; ?>" class="menu-btn">
                Doctor Profile
            </a>

            <a href="<?php echo htmlspecialchars(route_url("login")); ?>" class="menu-btn">
                Sign In
            </a>

        </nav>

    </aside>

    <section class="appointment-content">

        <div class="page-heading">

            <p>Premium Pet Clinic</p>

            <h1>Book Appointment</h1>

            <span>
                Choose your preferred date and time for your pet consultation.
            </span>

        </div>

        <div class="appointment-box">

            <div class="selected-doctor">

                <div class="doctor-image">

                    <img
                        src="assets/uploads/profiles/<?php echo htmlspecialchars(profile_image_file($profile_image)); ?>"
                        onerror="this.onerror=null; this.src='assets/uploads/profiles/default.svg';"
                        alt="<?php echo htmlspecialchars($doctor["full_name"]); ?>"
                    >

                </div>

                <h2>
                    <?php echo htmlspecialchars($doctor["full_name"]); ?>
                </h2>

                <h4>
                    <?php echo htmlspecialchars($doctor["specialization"]); ?>
                </h4>

                <p>
                    <?php echo htmlspecialchars($doctor["qualification"]); ?>
                </p>

                <div class="doctor-info-row">

                    <strong>Available Days</strong>

                    <span>
                        <?php echo htmlspecialchars($doctor["available_days"]); ?>
                    </span>

                </div>

                <div class="doctor-info-row">

                    <strong>Available Time</strong>

                    <span>
                        <?php echo htmlspecialchars($doctor["available_time"]); ?>
                    </span>

                </div>

                <div class="doctor-info-row">

                    <strong>Consultation Fee</strong>

                    <span>
                        ৳<?php echo number_format((float)$doctor["consultation_fee"], 2); ?>
                    </span>

                </div>

            </div>

            <div class="appointment-form-box">

                <h2>Appointment Information</h2>

                <?php if (!empty($error_message)): ?>

                    <div class="appointment-error">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>

                <?php endif; ?>

                <form action="<?php echo htmlspecialchars(route_url("book_appointment")); ?>&amp;doctor_id=<?php echo (int)$doctor["doctor_id"]; ?>" method="POST">

                    <input type="hidden" name="doctor_id" value="<?php echo (int)$doctor["doctor_id"]; ?>">

                    <div class="form-group">

                        <label for="pet_name">Pet Name</label>

                        <input
                            type="text"
                            id="pet_name"
                            name="pet_name"
                            value="<?php echo htmlspecialchars($pet_name); ?>"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="pet_type">Pet Type</label>

                        <select id="pet_type" name="pet_type" required>

                            <option value="">Select Pet Type</option>

                            <option value="Dog" <?php echo $pet_type === "Dog" ? "selected" : ""; ?>>
                                Dog
                            </option>

                            <option value="Cat" <?php echo $pet_type === "Cat" ? "selected" : ""; ?>>
                                Cat
                            </option>

                            <option value="Bird" <?php echo $pet_type === "Bird" ? "selected" : ""; ?>>
                                Bird
                            </option>

                            <option value="Fish" <?php echo $pet_type === "Fish" ? "selected" : ""; ?>>
                                Fish
                            </option>

                            <option value="Rabbit" <?php echo $pet_type === "Rabbit" ? "selected" : ""; ?>>
                                Rabbit
                            </option>

                            <option value="Other" <?php echo $pet_type === "Other" ? "selected" : ""; ?>>
                                Other
                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label for="guardian_name">Guardian Name</label>

                        <input
                            type="text"
                            id="guardian_name"
                            name="guardian_name"
                            value="<?php echo htmlspecialchars($guardian_name); ?>"
                            required
                        >

                    </div>

                    <div class="form-row">

                        <div class="form-group">

                            <label for="appointment_date">
                                Preferred Date
                            </label>

                            <input
                                type="date"
                                id="appointment_date"
                                name="appointment_date"
                                min="<?php echo date("Y-m-d"); ?>"
                                value="<?php echo htmlspecialchars($appointment_date); ?>"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label for="appointment_time">
                                Preferred Time
                            </label>

                            <input
                                type="time"
                                id="appointment_time"
                                name="appointment_time"
                                value="<?php echo htmlspecialchars($appointment_time); ?>"
                                required
                            >

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="reason">
                            Reason for Visit
                        </label>

                        <textarea
                            id="reason"
                            name="reason"
                            rows="4"
                            placeholder="Write a short reason for the appointment..."
                            required><?php echo htmlspecialchars($reason); ?></textarea>

                    </div>

                    <div class="form-actions">

                        <button type="submit" class="confirm-btn">
                            Confirm Booking
                        </button>

                        <a href="<?php echo htmlspecialchars(route_url("doctor_profile")); ?>&amp;doctor_id=<?php echo (int)$doctor["doctor_id"]; ?>" class="cancel-btn">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </section>

</main>

<footer class="appointment-footer">
    🐾 "Your pet deserves expert care." | PawCare Premium Pet Clinic © 2026
</footer>

</body>

</html
