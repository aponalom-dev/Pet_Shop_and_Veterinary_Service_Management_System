<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../models/admin_model.php';
require_once __DIR__ . '/../models/delivery_model.php';

function ajax_controller($conn) {
    $action = $_GET['action'] ?? '';
    $term = $_GET['q'] ?? '';
    $term = is_string($term) ? trim($term) : '';

    if ($action === 'check_username') {
        $username = $_GET['username'] ?? '';
        $username = is_string($username) ? trim($username) : '';
        if (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $username)) {
            json_out(['ok' => false, 'message' => 'Use 4-50 letters, numbers or underscores.']);
        }
        json_out(username_exists($conn, $username)
            ? ['ok' => false, 'message' => 'That username is taken.']
            : ['ok' => true, 'message' => 'That username is free.']);
    }

    if (!isset($_SESSION['user_id'])) {
        json_out(['error' => 'Please sign in first.'], 401);
    }

    $role = $_SESSION['role'] ?? '';
    $user_id = (int) $_SESSION['user_id'];

    switch ($action) {
        case 'search_accounts':
            if ($role !== 'admin') break;
            $account_role = $_GET['role'] ?? '';
            if (!is_string($account_role) || !in_array($account_role, ['', 'admin', 'customer', 'doctor', 'delivery'], true)) {
                json_out(['error' => 'Invalid account role.'], 400);
            }
            json_out(search_admin_accounts($conn, substr($term, 0, 100), $account_role));

        case 'search_inventory':
            if ($role !== 'admin') break;
            $type = $_GET['type'] ?? '';
            if (!in_array($type, ['pet', 'product'], true)) {
                json_out(['error' => 'Invalid inventory type.'], 400);
            }
            json_out($term === ''
                ? admin_inventory_list($conn, $type)
                : search_admin_inventory($conn, $type, $term));

        case 'stats':
            if ($role !== 'admin') break;
            $data = admin_overview_data($conn);
            json_out([
                'total_revenue' => $data['total_revenue'],
                'total_orders' => $data['total_orders'],
                'pets_in_stock' => $data['pets_in_stock'],
                'low_stock' => $data['low_stock']
            ]);

        case 'search_deliveries':
            if ($role !== 'delivery') break;
            $agent = find_delivery_agent($conn, $user_id);
            if (!$agent) break;
            $status = $_GET['status'] ?? '';
            if ($status !== '' && !in_array($status, ['Assigned', 'Out for Delivery', 'Delivered', 'Failed', 'Cancelled'], true)) {
                $status = '';
            }
            json_out(list_assigned_delivery_orders($conn, $user_id, $agent['company_name'], $term, $status));
    }

    json_out(['error' => 'You are not allowed to use this endpoint.'], 403);
}
