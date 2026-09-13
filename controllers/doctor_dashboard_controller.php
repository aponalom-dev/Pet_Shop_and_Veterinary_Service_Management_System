<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/doctor_work_model.php';

function doctor_dashboard_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "doctor") {
        header("Location: " . route_url("login"));
        exit;
    }
    $user_id = (int) $_SESSION["user_id"];
    $doctor = find_doctor_by_user($conn, $user_id);
    if (!$doctor) {
        header("Location: " . route_url("login"));
        exit;
    }
    $doctor_id = (int) $doctor["doctor_id"];
    $total_appointments = doctor_appointment_count($conn, $doctor_id);
    $pending_appointments = doctor_appointment_count($conn, $doctor_id, "Pending");
    $completed_appointments = doctor_appointment_count($conn, $doctor_id, "Completed");
    $today_appointments = doctor_today_appointment_count($conn, $doctor_id, date("Y-m-d"));
    $upcoming_appointments = list_upcoming_doctor_appointments($conn, $doctor_id);
    $profile_image = trim($doctor["profile_image"] ?? "");
    if (empty($profile_image)) $profile_image = "default.png";
    require __DIR__ . '/../views/doctor/dashboard.php';
}
