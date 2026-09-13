<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/order_model.php';

function customer_orders_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }
    $customer_id = (int) $_SESSION["user_id"];
    $orders = list_customer_orders($conn, $customer_id);
    require __DIR__ . '/../views/customer/orders.php';
}
