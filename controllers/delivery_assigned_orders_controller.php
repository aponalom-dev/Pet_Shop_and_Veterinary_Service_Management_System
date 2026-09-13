<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/delivery_model.php';

function delivery_assigned_orders_controller($conn) {
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

    if ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST["start_delivery"]) || isset($_POST["mark_delivered"]))) {
        $delivery_id = (int) ($_POST["delivery_id"] ?? 0);
        $starting = isset($_POST["start_delivery"]);
        $from_status = $starting ? "Assigned" : "Out for Delivery";
        $to_status = $starting ? "Out for Delivery" : "Delivered";
        $message = "invalid";
        if ($delivery_id > 0) {
            $delivery = find_assigned_delivery_state($conn, $delivery_id, $user_id, $company_name, $from_status);
            if ($delivery) {
                $saved = change_delivery_state($conn, $delivery_id, $user_id, (int) $delivery["order_id"], $company_name, $to_status);
                $message = $saved ? ($starting ? "started" : "delivered") : "error";
            }
        }
        header("Location: " . route_url("delivery/assigned_orders", "message=" . $message));
        exit;
    }

    $message = $_GET["message"] ?? "";
    $search = trim($_GET["search"] ?? "");
    $status = trim($_GET["status"] ?? "");
    $allowed_status = ["Assigned", "Out for Delivery", "Delivered", "Failed", "Cancelled"];
    if ($status !== "" && !in_array($status, $allowed_status, true)) $status = "";
    $orders = list_assigned_delivery_orders($conn, $user_id, $company_name, $search, $status);
    require __DIR__ . '/../views/delivery/assigned_orders.php';
}
