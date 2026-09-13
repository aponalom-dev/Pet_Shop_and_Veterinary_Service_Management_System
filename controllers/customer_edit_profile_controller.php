<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/user_model.php';

function customer_edit_profile_controller($conn) {
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
        $full_name = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $address = trim($_POST["address"] ?? "");

        if ($full_name === "" || $email === "" || $phone === "" || $address === "") {
            $error = "Please fill in all fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } elseif (customer_email_is_taken($conn, $email, $customer_id)) {
            $error = "This email is already used by another account.";
        } elseif (update_customer_profile($conn, $customer_id, $full_name, $email, $phone, $address)) {
            $_SESSION["full_name"] = $full_name;
            header("Location: " . route_url("customer/profile", "updated=1"));
            exit;
        } else {
            $error = "Profile could not be updated.";
        }

        $customer["full_name"] = $full_name;
        $customer["email"] = $email;
        $customer["phone"] = $phone;
        $customer["address"] = $address;
    }

    require __DIR__ . '/../views/customer/edit_profile.php';
}
