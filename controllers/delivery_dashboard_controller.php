<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/delivery_model.php';

function delivery_dashboard_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "delivery") {
        header("Location: " . route_url("login"));
        exit;
    }
    $user_id = (int) $_SESSION["user_id"];
    $agent = find_delivery_agent($conn, $user_id);
    if (!$agent) {
        header("Location: " . route_url("login"));
        exit;
    }
    $agent_name = $agent["full_name"];
    $company_name = $agent["company_name"];
    $stats = delivery_dashboard_stats($conn, $user_id, $company_name);
    $total_assigned = (int) ($stats["total_assigned"] ?? 0);
    $out_for_delivery = (int) ($stats["out_for_delivery"] ?? 0);
    $delivered_today = (int) ($stats["delivered_today"] ?? 0);
    $total_delivered = (int) ($stats["total_delivered"] ?? 0);
    $success_rate = $total_assigned > 0 ? round(($total_delivered / $total_assigned) * 100) : 0;
    $orders = list_recent_delivery_orders($conn, $user_id, $company_name);
    require __DIR__ . '/../views/delivery/dashboard.php';
}
