<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/delivery_model.php';
require_once __DIR__ . '/../models/order_model.php';

function delivery_order_details_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "delivery") {
        header("Location: " . route_url("login"));
        exit;
    }
    $user_id = (int) $_SESSION["user_id"];
    $delivery_id = (int) ($_GET["id"] ?? 0);
    $agent = find_delivery_agent($conn, $user_id);
    if (!$agent) {
        header("Location: " . route_url("login"));
        exit;
    }
    $agent_name = $agent["full_name"];
    $company_name = $agent["company_name"];
    $order = $delivery_id > 0 ? find_delivery_order($conn, $delivery_id, $user_id, $company_name) : null;
    if (!$order) {
        header("Location: " . route_url("delivery/assigned_orders"));
        exit;
    }
    $items = list_order_items($conn, (int) $order["order_id"]);
    require __DIR__ . '/../views/delivery/order_details.php';
}
