<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/order_model.php';

function customer_order_details_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }
    $customer_id = (int) $_SESSION["user_id"];
    $order_id = (int) ($_GET["order_id"] ?? 0);
    $order = $order_id > 0 ? find_customer_order($conn, $order_id, $customer_id) : null;
    if (!$order) {
        header("Location: " . route_url("customer/profile"));
        exit;
    }
    $order_items = list_order_items($conn, $order_id);
    require __DIR__ . '/../views/customer/order_details.php';
}
