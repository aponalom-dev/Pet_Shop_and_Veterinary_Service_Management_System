<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/admin_model.php';

function admin_delivery_tracking_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: " . route_url("login"));
        exit;
    }
    $success_message = "";
    $error_message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_delivery"])) {
        $delivery_id = (int) ($_POST["delivery_id"] ?? 0);
        $delivery_status = $_POST["delivery_status"] ?? "";
        $allowed_status = ["Assigned", "Picked Up", "Out for Delivery", "Delivered", "Failed", "Cancelled"];
        if ($delivery_id <= 0 || !in_array($delivery_status, $allowed_status, true)) {
            $error_message = "Invalid delivery information.";
        } elseif (admin_update_delivery_status($conn, $delivery_id, $delivery_status)) {
            $success_message = "Delivery status updated successfully.";
        } else {
            $error_message = "Delivery status could not be updated.";
        }
    }
    $data = admin_delivery_data($conn);
    $partners = $data["partners"];
    $partner_stats = $data["partner_stats"];
    $total_orders = $data["total_orders"];
    $deliveries = $data["deliveries"];
    $partner_names = $data["partner_names"];
    $partner_values = $data["partner_values"];
    require __DIR__ . '/../views/admin/delivery_tracking.php';
}
