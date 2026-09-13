<?php

function find_doctor_by_user($conn, $user_id) {
    $sql = "SELECT d.doctor_id, d.specialization, d.qualification, d.experience_years, d.consultation_fee, d.available_days, d.available_time, d.status, u.full_name, u.email, u.phone, u.profile_image FROM doctors d JOIN users u ON d.user_id = u.user_id WHERE d.user_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $doctor = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $doctor;
}

function doctor_appointment_count($conn, $doctor_id, $status = "") {
    $sql = "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ?";
    if ($status !== "") {
        $sql .= " AND status = ?";
    }
    $stmt = mysqli_prepare($conn, $sql);
    if ($status === "") {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    } else {
        mysqli_stmt_bind_param($stmt, "is", $doctor_id, $status);
    }
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (int) $row["total"];
}

function doctor_today_appointment_count($conn, $doctor_id, $today) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ? AND appointment_date = ?");
    mysqli_stmt_bind_param($stmt, "is", $doctor_id, $today);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (int) $row["total"];
}

function list_upcoming_doctor_appointments($conn, $doctor_id) {
    $sql = "SELECT a.appointment_id, a.pet_name, a.pet_type, a.appointment_date, a.appointment_time, a.status, u.full_name AS customer_name FROM appointments a JOIN users u ON a.customer_id = u.user_id WHERE a.doctor_id = ? AND a.appointment_date >= CURDATE() AND a.status != 'Cancelled' ORDER BY a.appointment_date ASC, a.appointment_time ASC LIMIT 6";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $appointments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $appointments;
}

function find_doctor_appointment($conn, $appointment_id, $doctor_id) {
    $stmt = mysqli_prepare($conn, "SELECT appointment_id, status FROM appointments WHERE appointment_id = ? AND doctor_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ii", $appointment_id, $doctor_id);
    mysqli_stmt_execute($stmt);
    $appointment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $appointment;
}

function update_doctor_appointment_status($conn, $appointment_id, $doctor_id, $status) {
    $stmt = mysqli_prepare($conn, "UPDATE appointments SET status = ? WHERE appointment_id = ? AND doctor_id = ?");
    mysqli_stmt_bind_param($stmt, "sii", $status, $appointment_id, $doctor_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $saved;
}

function list_doctor_appointments($conn, $doctor_id, $filter) {
    $sql = "SELECT a.appointment_id, a.pet_name, a.pet_type, a.appointment_date, a.appointment_time, a.reason, a.status, a.created_at, u.full_name AS customer_name, u.phone AS customer_phone FROM appointments a JOIN users u ON a.customer_id = u.user_id WHERE a.doctor_id = ?";
    if ($filter !== "all") {
        $sql .= " AND a.status = ?";
    }
    $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
    $stmt = mysqli_prepare($conn, $sql);
    if ($filter === "all") {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    } else {
        $status = ucfirst($filter);
        mysqli_stmt_bind_param($stmt, "is", $doctor_id, $status);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $appointments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $appointments;
}

function list_doctor_medical_records($conn, $doctor_id) {
    $sql = "SELECT mr.record_id, mr.appointment_id, mr.diagnosis, mr.prescription, mr.treatment_notes, mr.next_visit_date, mr.created_at, a.pet_name, a.pet_type, a.appointment_date, a.appointment_time, u.full_name AS customer_name, u.phone AS customer_phone FROM medical_records mr JOIN appointments a ON mr.appointment_id = a.appointment_id JOIN users u ON a.customer_id = u.user_id WHERE a.doctor_id = ? ORDER BY mr.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $records = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $records;
}
