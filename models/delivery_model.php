<?php

function delivery_company_cards($conn, $companies) {
    $cards = [];
    foreach ($companies as $company) {
        $agent_sql = "SELECT u.full_name FROM delivery_agents da JOIN users u ON da.user_id = u.user_id WHERE da.company_name = ? AND da.status = 'Active' LIMIT 1";
        $stmt = mysqli_prepare($conn, $agent_sql);
        mysqli_stmt_bind_param($stmt, "s", $company);
        mysqli_stmt_execute($stmt);
        $agent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        $order_sql = "SELECT COUNT(*) AS total FROM orders WHERE delivery_method = ? AND order_status NOT IN ('Delivered', 'Cancelled')";
        $stmt = mysqli_prepare($conn, $order_sql);
        mysqli_stmt_bind_param($stmt, "s", $company);
        mysqli_stmt_execute($stmt);
        $order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        $cards[] = [
            "name" => $company,
            "agent" => $agent["full_name"] ?? "No Agent Assigned",
            "pending" => $order["total"] ?? 0
        ];
    }
    return $cards;
}

function find_delivery_agent($conn, $user_id, $active_only = true) {
    $sql = "SELECT u.full_name, u.username, u.email, u.phone, u.gender, u.address, u.role, u.profile_image, u.status, da.company_name, da.status AS agent_status FROM users u JOIN delivery_agents da ON u.user_id = da.user_id WHERE u.user_id = ?";
    if ($active_only) {
        $sql .= " AND da.status = 'Active'";
    }
    $sql .= " LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $agent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $agent;
}

function delivery_dashboard_stats($conn, $user_id, $company) {
    $sql = "SELECT COUNT(*) AS total_assigned, SUM(CASE WHEN d.delivery_status = 'Out for Delivery' THEN 1 ELSE 0 END) AS out_for_delivery, SUM(CASE WHEN d.delivery_status = 'Delivered' AND DATE(d.delivered_at) = CURDATE() THEN 1 ELSE 0 END) AS delivered_today, SUM(CASE WHEN d.delivery_status = 'Delivered' THEN 1 ELSE 0 END) AS total_delivered FROM deliveries d JOIN orders o ON d.order_id = o.order_id WHERE d.delivery_agent_id = ? AND o.delivery_method = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $company);
    mysqli_stmt_execute($stmt);
    $stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $stats;
}

function list_recent_delivery_orders($conn, $user_id, $company) {
    $sql = "SELECT d.delivery_id, d.delivery_status, o.order_id, o.total_amount, o.delivery_address, u.full_name AS customer_name FROM deliveries d JOIN orders o ON d.order_id = o.order_id JOIN users u ON o.customer_id = u.user_id WHERE d.delivery_agent_id = ? AND o.delivery_method = ? ORDER BY d.assigned_at DESC LIMIT 5";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $company);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) $orders[] = $row;
    mysqli_stmt_close($stmt);
    return $orders;
}

function list_delivery_history($conn, $user_id, $company) {
    $sql = "SELECT d.delivery_id, d.delivery_status, d.assigned_at, d.delivered_at, d.delivery_note, o.order_id, o.total_amount, o.delivery_address, o.order_date, u.full_name AS customer_name, u.phone AS customer_phone FROM deliveries d JOIN orders o ON d.order_id = o.order_id JOIN users u ON o.customer_id = u.user_id WHERE d.delivery_agent_id = ? AND o.delivery_method = ? AND d.delivery_status IN ('Delivered', 'Failed', 'Cancelled') ORDER BY d.assigned_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $company);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    mysqli_stmt_close($stmt);
    return $rows;
}

function find_delivery_order($conn, $delivery_id, $user_id, $company) {
    $sql = "SELECT d.delivery_id, d.delivery_status, d.assigned_at, d.delivered_at, d.delivery_note, o.order_id, o.total_amount, o.delivery_address, o.delivery_method, o.payment_method, o.payment_status, o.order_status, o.order_date, u.full_name AS customer_name, u.email AS customer_email, u.phone AS customer_phone FROM deliveries d JOIN orders o ON d.order_id = o.order_id JOIN users u ON o.customer_id = u.user_id WHERE d.delivery_id = ? AND d.delivery_agent_id = ? AND o.delivery_method = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $delivery_id, $user_id, $company);
    mysqli_stmt_execute($stmt);
    $order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $order;
}

function update_delivery_profile($conn, $user_id, $full_name, $phone, $address) {
    $sql = "UPDATE users SET full_name = ?, phone = ?, address = ? WHERE user_id = ? AND role = 'delivery'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $full_name, $phone, $address, $user_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $saved;
}

function find_assigned_delivery_state($conn, $delivery_id, $user_id, $company, $status) {
    $sql = "SELECT d.delivery_id, d.order_id FROM deliveries d JOIN orders o ON d.order_id = o.order_id WHERE d.delivery_id = ? AND d.delivery_agent_id = ? AND o.delivery_method = ? AND d.delivery_status = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $delivery_id, $user_id, $company, $status);
    mysqli_stmt_execute($stmt);
    $delivery = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $delivery;
}

function change_delivery_state($conn, $delivery_id, $user_id, $order_id, $company, $to_status) {
    if ($to_status === "Delivered") {
        $delivery_sql = "UPDATE deliveries SET delivery_status = 'Delivered', delivered_at = NOW() WHERE delivery_id = ? AND delivery_agent_id = ?";
        $order_sql = "UPDATE orders SET order_status = 'Delivered' WHERE order_id = ? AND delivery_method = ?";
    } else {
        $delivery_sql = "UPDATE deliveries SET delivery_status = 'Out for Delivery' WHERE delivery_id = ? AND delivery_agent_id = ?";
        $order_sql = "UPDATE orders SET order_status = 'Shipped' WHERE order_id = ? AND delivery_method = ?";
    }
    if (!mysqli_begin_transaction($conn)) return false;
    $stmt = mysqli_prepare($conn, $delivery_sql);
    if (!$stmt) {
        mysqli_rollback($conn);
        return false;
    }
    mysqli_stmt_bind_param($stmt, "ii", $delivery_id, $user_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (!$saved) {
        mysqli_rollback($conn);
        return false;
    }
    $stmt = mysqli_prepare($conn, $order_sql);
    if (!$stmt) {
        mysqli_rollback($conn);
        return false;
    }
    mysqli_stmt_bind_param($stmt, "is", $order_id, $company);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (!$saved) {
        mysqli_rollback($conn);
        return false;
    }
    if (!mysqli_commit($conn)) {
        mysqli_rollback($conn);
        return false;
    }
    return true;
}

function list_assigned_delivery_orders($conn, $user_id, $company, $search, $status) {
    $sql = "SELECT d.delivery_id, d.delivery_status, d.assigned_at, d.delivered_at, d.delivery_note, o.order_id, o.total_amount, o.delivery_address, o.order_status, o.payment_method, o.payment_status, o.order_date, u.full_name AS customer_name, u.phone AS customer_phone FROM deliveries d JOIN orders o ON d.order_id = o.order_id JOIN users u ON o.customer_id = u.user_id WHERE d.delivery_agent_id = ? AND o.delivery_method = ?";
    if ($search !== "") $sql .= " AND (CAST(o.order_id AS CHAR) LIKE ? OR u.full_name LIKE ?)";
    if ($status !== "") $sql .= " AND d.delivery_status = ?";
    $sql .= " ORDER BY d.assigned_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    $order_search = "%" . ltrim($search, "#") . "%";
    $customer_search = "%" . $search . "%";
    if ($search !== "" && $status !== "") {
        mysqli_stmt_bind_param($stmt, "issss", $user_id, $company, $order_search, $customer_search, $status);
    } elseif ($search !== "") {
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $company, $order_search, $customer_search);
    } elseif ($status !== "") {
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $company, $status);
    } else {
        mysqli_stmt_bind_param($stmt, "is", $user_id, $company);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) $orders[] = $row;
    mysqli_stmt_close($stmt);
    return $orders;
}
