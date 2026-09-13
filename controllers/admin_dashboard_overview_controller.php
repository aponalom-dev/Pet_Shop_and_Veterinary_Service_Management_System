<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/admin_model.php';

function admin_dashboard_overview_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: " . route_url("login"));
        exit;
    }

    $data = admin_overview_data($conn);
    $total_revenue = $data["total_revenue"];
    $total_orders = $data["total_orders"];
    $pets_in_stock = $data["pets_in_stock"];
    $low_stock = $data["low_stock"];
    $recent_orders = $data["recent_orders"];
    require __DIR__ . '/../views/admin/dashboard_overview.php';
}
