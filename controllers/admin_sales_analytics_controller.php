<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/admin_model.php';

function admin_sales_analytics_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: " . route_url("login"));
        exit;
    }
    $data = admin_sales_data($conn);
    $total_revenue = $data["total_revenue"];
    $total_orders = $data["total_orders"];
    $paid_orders = $data["paid_orders"];
    $average_order_value = $data["average_order_value"];
    $category_sales = $data["category_sales"];
    $top_items = $data["top_items"];
    $recent_sales = $data["recent_sales"];
    $category_names = $data["category_names"];
    $category_values = $data["category_values"];
    require __DIR__ . '/../views/admin/sales_analytics.php';
}
