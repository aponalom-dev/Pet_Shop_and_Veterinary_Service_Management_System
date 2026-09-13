<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../models/order_model.php';

function customer_profile_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }
    $customer_id = (int) $_SESSION["user_id"];
    $customer = find_customer_account($conn, $customer_id);
    if (!$customer) {
        header("Location: " . route_url("login"));
        exit;
    }
    $recent_orders = list_customer_orders($conn, $customer_id, 3);
    require __DIR__ . '/../views/customer/profile.php';
}
