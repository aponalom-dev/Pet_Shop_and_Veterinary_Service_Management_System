<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/delivery_model.php';

function delivery_history_controller($conn) {
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
    $history_rows = list_delivery_history($conn, $user_id, $company_name);
    require __DIR__ . '/../views/delivery/history.php';
}
