<?php

function find_customer_appointment_token($conn, $appointment_id, $customer_id) {
    $sql = "SELECT a.appointment_id, a.pet_name, a.pet_type, a.appointment_date, a.appointment_time, a.reason, a.status, c.full_name AS customer_name, d.specialization, du.full_name AS doctor_name FROM appointments a JOIN users c ON a.customer_id = c.user_id JOIN doctors d ON a.doctor_id = d.doctor_id JOIN users du ON d.user_id = du.user_id WHERE a.appointment_id = ? AND a.customer_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $appointment_id, $customer_id);
    mysqli_stmt_execute($stmt);
    $appointment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $appointment;
}

function doctor_is_available_for_booking($conn, $doctor_id) {
    $sql = "SELECT d.doctor_id FROM doctors d JOIN users u ON d.user_id = u.user_id WHERE d.doctor_id = ? AND d.status = 'Available' AND u.status = 'active' LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (bool) $found;
}

function appointment_slot_is_taken($conn, $doctor_id, $appointment_date, $appointment_time) {
    $sql = "SELECT appointment_id FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'Cancelled' LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $doctor_id, $appointment_date, $appointment_time);
    mysqli_stmt_execute($stmt);
    $found = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (bool) $found;
}

function create_appointment($conn, $customer_id, $doctor_id, $pet_name, $pet_type, $appointment_date, $appointment_time, $reason) {
    $sql = "INSERT INTO appointments (customer_id, doctor_id, pet_name, pet_type, appointment_date, appointment_time, reason, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisssss", $customer_id, $doctor_id, $pet_name, $pet_type, $appointment_date, $appointment_time, $reason);
    $saved = mysqli_stmt_execute($stmt);
    $appointment_id = $saved ? mysqli_insert_id($conn) : 0;
    mysqli_stmt_close($stmt);
    return $appointment_id;
}
