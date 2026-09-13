<?php

function list_public_doctors($conn) {
    $doctors = [];
    $sql = "SELECT d.doctor_id, d.specialization, d.qualification, d.experience_years, d.consultation_fee, d.available_days, d.available_time, d.bio, d.status, u.full_name, u.profile_image FROM doctors d JOIN users u ON d.user_id = u.user_id WHERE u.status = 'active' ORDER BY d.doctor_id ASC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $doctors[] = $row;
        }
    }
    return $doctors;
}

function find_public_doctor($conn, $doctor_id) {
    $sql = "SELECT d.doctor_id, d.specialization, d.qualification, d.experience_years, d.consultation_fee, d.available_days, d.available_time, d.bio, d.status, u.full_name, u.profile_image FROM doctors d JOIN users u ON d.user_id = u.user_id WHERE d.doctor_id = ? AND u.status = 'active' LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $doctor = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $doctor;
}

function count_doctor_treatments($conn, $doctor_id) {
    $sql = "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ? AND status = 'Completed'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (int) $row["total"];
}

function get_doctor_rating_summary($conn, $doctor_id) {
    $sql = "SELECT ROUND(AVG(rating), 1) AS average_rating, COUNT(*) AS total_reviews FROM reviews WHERE item_type = 'doctor' AND item_id = ? AND status = 'Visible'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}

function list_doctor_reviews($conn, $doctor_id) {
    $reviews = [];
    $sql = "SELECT r.rating, r.comment, r.created_at, u.full_name FROM reviews r JOIN users u ON r.customer_id = u.user_id WHERE r.item_type = 'doctor' AND r.item_id = ? AND r.status = 'Visible' ORDER BY r.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $reviews;
}
