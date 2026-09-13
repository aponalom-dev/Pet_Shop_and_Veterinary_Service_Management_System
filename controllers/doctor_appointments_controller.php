<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/doctor_work_model.php';

function doctor_appointments_controller($conn) {
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
    $success_message = "";
    $error_message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $appointment_id = (int) ($_POST["appointment_id"] ?? 0);
        $new_status = trim($_POST["status"] ?? "");
        if ($appointment_id <= 0 || !in_array($new_status, ["Confirmed", "Completed", "Cancelled"], true)) {
            $error_message = "Invalid appointment update.";
        } else {
            $appointment = find_doctor_appointment($conn, $appointment_id, $doctor_id);
            if (!$appointment) {
                $error_message = "Appointment not found.";
            } elseif ($appointment["status"] === "Completed" || $appointment["status"] === "Cancelled") {
                $error_message = "This appointment cannot be changed anymore.";
            } elseif (update_doctor_appointment_status($conn, $appointment_id, $doctor_id, $new_status)) {
                $success_message = "Appointment status updated successfully.";
            } else {
                $error_message = "Failed to update appointment.";
            }
        }
    }
    $filter = $_GET["filter"] ?? "all";
    if (!in_array($filter, ["all", "pending", "confirmed", "completed", "cancelled"], true)) $filter = "all";
    $appointments = list_doctor_appointments($conn, $doctor_id, $filter);
    $profile_image = trim($doctor["profile_image"] ?? "");
    if (empty($profile_image)) $profile_image = "default.png";
    require __DIR__ . '/../views/doctor/appointments.php';
}
