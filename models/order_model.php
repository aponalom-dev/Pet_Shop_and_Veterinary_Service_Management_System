<?php

function list_customer_orders($conn, $customer_id, $limit = 0) {
    $sql = "SELECT order_id, total_amount, delivery_method, payment_method, payment_status, order_status, order_date FROM orders WHERE customer_id = ? ORDER BY order_date DESC";
    if ($limit > 0) {
        $sql .= " LIMIT " . (int) $limit;
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $orders;
}

function find_customer_order($conn, $order_id, $customer_id) {
    $sql = "SELECT o.order_id, o.total_amount, o.delivery_address, o.delivery_method, o.payment_method, o.payment_status, o.order_status, o.order_date, u.full_name, u.username, u.email, u.phone FROM orders o JOIN users u ON o.customer_id = u.user_id WHERE o.order_id = ? AND o.customer_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $customer_id);
    mysqli_stmt_execute($stmt);
    $order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $order;
}

function list_order_items($conn, $order_id) {
    $sql = "SELECT item_type, item_name, price, quantity, subtotal FROM order_items WHERE order_id = ? ORDER BY order_item_id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $items;
}
