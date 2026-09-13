<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/delivery_model.php';

function delivery_profile_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "delivery") {
        header("Location: " . route_url("login"));
        exit;
    }
    $user_id = (int) $_SESSION["user_id"];
    $message = "";
    $message_type = "";
    $profile = find_delivery_agent($conn, $user_id, false);
    if (!$profile) {
        header("Location: " . route_url("login"));
        exit;
    }
    $company_name = $profile["company_name"];
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_profile"])) {
        $full_name = trim($_POST["full_name"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $address = trim($_POST["address"] ?? "");
        if ($full_name === "" || $phone === "" || $address === "") {
            $message = "Full name, phone number and address are required.";
            $message_type = "error";
        } elseif (update_delivery_profile($conn, $user_id, $full_name, $phone, $address)) {
            $_SESSION["full_name"] = $full_name;
            $message = "Profile updated successfully.";
            $message_type = "success";
            $profile["full_name"] = $full_name;
            $profile["phone"] = $phone;
            $profile["address"] = $address;
        } else {
            $message = "Profile update failed. Please try again.";
            $message_type = "error";
        }
    }
    $agent_name = $profile["full_name"];
    require __DIR__ . '/../views/delivery/profile.php';
}
