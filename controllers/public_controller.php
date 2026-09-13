<?php
require_once __DIR__ . '/../helpers/helpers.php';

require_once __DIR__ . '/../models/doctor_model.php';
require_once __DIR__ . '/../models/appointment_model.php';

function specialist_doctors_controller($conn) {
    $doctors = list_public_doctors($conn);
    require __DIR__ . '/../views/public/specialist_doctors.php';
}

function doctor_profile_controller($conn) {
    $doctor_id = isset($_GET["doctor_id"]) ? (int) $_GET["doctor_id"] : 0;
    $doctor = $doctor_id > 0 ? find_public_doctor($conn, $doctor_id) : null;

    if (!$doctor) {
        header("Location: " . route_url("specialist_doctors"));
        exit;
    }

    $completed_treatments = count_doctor_treatments($conn, $doctor_id);
    $rating_data = get_doctor_rating_summary($conn, $doctor_id);
    $average_rating = $rating_data["average_rating"] ?? 0;
    $total_reviews = (int) ($rating_data["total_reviews"] ?? 0);
    $reviews = list_doctor_reviews($conn, $doctor_id);
    $profile_image = trim($doctor["profile_image"] ?? "");
    if (empty($profile_image)) {
        $profile_image = "default.png";
    }

    require __DIR__ . '/../views/public/doctor_profile.php';
}

function pet_reviews_controller() {
    $review_image = "assets/images/Pet_review_page.png";
    require __DIR__ . '/../views/public/pet_reviews.php';
}

function appointment_token_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }

    $customer_id = (int) $_SESSION["user_id"];
    $appointment_id = isset($_GET["appointment_id"]) ? (int) $_GET["appointment_id"] : 0;
    if ($appointment_id <= 0) {
        header("Location: " . route_url("specialist_doctors"));
        exit;
    }

    $appointment = find_customer_appointment_token($conn, $appointment_id, $customer_id);
    if (!$appointment) {
        header("Location: " . route_url("specialist_doctors"));
        exit;
    }

    require __DIR__ . '/../views/public/appointment_token.php';
}

function book_appointment_controller($conn) {
    $doctor_id = isset($_GET["doctor_id"]) ? (int) $_GET["doctor_id"] : 0;
    $doctor = $doctor_id > 0 ? find_public_doctor($conn, $doctor_id) : null;
    if (!$doctor || $doctor["status"] !== "Available") {
        header("Location: " . route_url("specialist_doctors"));
        exit;
    }

    $profile_image = trim($doctor["profile_image"] ?? "");
    if (empty($profile_image)) {
        $profile_image = "default.png";
    }

    $pet_name = "";
    $pet_type = "";
    $guardian_name = "";
    $appointment_date = "";
    $appointment_time = "";
    $reason = "";
    $error_message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $pet_name = trim($_POST["pet_name"] ?? "");
        $pet_type = trim($_POST["pet_type"] ?? "");
        $guardian_name = trim($_POST["guardian_name"] ?? "");
        $appointment_date = trim($_POST["appointment_date"] ?? "");
        $appointment_time = trim($_POST["appointment_time"] ?? "");
        $reason = trim($_POST["reason"] ?? "");

        if (empty($pet_name) || empty($pet_type) || empty($guardian_name) || empty($appointment_date) || empty($appointment_time) || empty($reason)) {
            $error_message = "Please fill in all appointment information.";
        } elseif ($appointment_date < date("Y-m-d")) {
            $error_message = "Please select a valid appointment date.";
        } else {
            $_SESSION["pending_appointment"] = [
                "doctor_id" => $doctor_id,
                "pet_name" => $pet_name,
                "pet_type" => $pet_type,
                "guardian_name" => $guardian_name,
                "appointment_date" => $appointment_date,
                "appointment_time" => $appointment_time,
                "reason" => $reason
            ];

            if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
                $_SESSION["redirect_after_login"] = "confirm_appointment";
                header("Location: " . route_url("login"));
                exit;
            }
            header("Location: " . route_url("confirm_appointment"));
            exit;
        }
    }

    require __DIR__ . '/../views/public/book_appointment.php';
}

function confirm_appointment_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }
    if (empty($_SESSION["pending_appointment"])) {
        header("Location: " . route_url("specialist_doctors"));
        exit;
    }

    $customer_id = (int) $_SESSION["user_id"];
    $appointment = $_SESSION["pending_appointment"];
    $doctor_id = (int) ($appointment["doctor_id"] ?? 0);
    $pet_name = trim($appointment["pet_name"] ?? "");
    $pet_type = trim($appointment["pet_type"] ?? "");
    $appointment_date = trim($appointment["appointment_date"] ?? "");
    $appointment_time = trim($appointment["appointment_time"] ?? "");
    $reason = trim($appointment["reason"] ?? "");

    if ($doctor_id <= 0 || empty($pet_name) || empty($pet_type) || empty($appointment_date) || empty($appointment_time) || empty($reason)
        || !doctor_is_available_for_booking($conn, $doctor_id)) {
        unset($_SESSION["pending_appointment"]);
        header("Location: " . route_url("specialist_doctors"));
        exit;
    }

    if (appointment_slot_is_taken($conn, $doctor_id, $appointment_date, $appointment_time)) {
        $_SESSION["appointment_error"] = "This appointment time is already booked. Please choose another time.";
        header("Location: " . route_url("book_appointment", "doctor_id=" . $doctor_id));
        exit;
    }

    $appointment_id = create_appointment($conn, $customer_id, $doctor_id, $pet_name, $pet_type, $appointment_date, $appointment_time, $reason);
    if ($appointment_id > 0) {
        unset($_SESSION["pending_appointment"]);
        header("Location: " . route_url("appointment_token", "appointment_id=" . $appointment_id));
        exit;
    }

    header("Location: " . route_url("specialist_doctors"));
    exit;
}
