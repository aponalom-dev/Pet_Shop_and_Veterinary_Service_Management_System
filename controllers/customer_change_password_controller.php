<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/user_model.php';

function customer_change_password_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }

    $customer_id = (int) $_SESSION["user_id"];
    $error = "";
    $customer = find_customer_account($conn, $customer_id);
    if (!$customer) {
        header("Location: " . route_url("login"));
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $current_password = $_POST["current_password"] ?? "";
        $new_password = $_POST["new_password"] ?? "";
        $confirm_password = $_POST["confirm_password"] ?? "";

        if ($current_password === "" || $new_password === "" || $confirm_password === "") {
            $error = "Please fill in all password fields.";
        } elseif (!password_verify($current_password, $customer["password"])) {
            $error = "Current password is incorrect.";
        } elseif (strlen($new_password) < 8) {
            $error = "New password must be at least 8 characters.";
        } elseif (password_verify($new_password, $customer["password"])) {
            $error = "New password must be different from current password.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match.";
        } else {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            if (update_customer_password($conn, $customer_id, $hash)) {
                header("Location: " . route_url("customer/profile", "password_changed=1"));
                exit;
            }
            $error = "Password could not be changed. Please try again.";
        }
    }

    require __DIR__ . '/../views/customer/change_password.php';
}
