<?php

function checkout_failure($conn, $message) {
    mysqli_rollback($conn);
    return ["order_id" => 0, "error" => $message];
}

function place_customer_order($conn, $customer_id, $customer, $cart_items, $grand_total, $delivery_method, $payment_method) {
    if (!mysqli_begin_transaction($conn)) {
        return ["order_id" => 0, "error" => "Order could not be started."];
    }

    foreach ($cart_items as $item) {
        $stock_sql = $item["item_type"] === "pet"
            ? "SELECT stock FROM pets WHERE pet_id = ? FOR UPDATE"
            : "SELECT stock FROM products WHERE product_id = ? FOR UPDATE";
        $item_id = (int) $item["item_id"];
        $stmt = mysqli_prepare($conn, $stock_sql);
        if (!$stmt) return checkout_failure($conn, "Stock could not be checked.");
        mysqli_stmt_bind_param($stmt, "i", $item_id);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return checkout_failure($conn, "Stock could not be checked.");
        }
        $stock_row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        if (!$stock_row || (int) $stock_row["stock"] < (int) $item["quantity"]) {
            return checkout_failure($conn, "One or more items do not have enough stock.");
        }
    }

    $order_sql = "INSERT INTO orders (customer_id, total_amount, delivery_address, delivery_method, payment_method, payment_status, order_status) VALUES (?, ?, ?, ?, ?, 'Pending', 'Pending')";
    $delivery_address = $customer["address"] ?? "";
    $stmt = mysqli_prepare($conn, $order_sql);
    if (!$stmt) return checkout_failure($conn, "Order could not be saved.");
    mysqli_stmt_bind_param($stmt, "idsss", $customer_id, $grand_total, $delivery_address, $delivery_method, $payment_method);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return checkout_failure($conn, "Order could not be saved.");
    }
    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    foreach ($cart_items as $item) {
        $item_type = $item["item_type"];
        $item_id = (int) $item["item_id"];
        $item_name = $item["item_name"];
        $price = (float) $item["item_price"];
        $quantity = (int) $item["quantity"];
        $subtotal = (float) $item["subtotal"];
        $item_sql = "INSERT INTO order_items (order_id, item_type, item_id, item_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $item_sql);
        if (!$stmt) return checkout_failure($conn, "Order item could not be saved.");
        mysqli_stmt_bind_param($stmt, "isisdid", $order_id, $item_type, $item_id, $item_name, $price, $quantity, $subtotal);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return checkout_failure($conn, "Order item could not be saved.");
        }
        mysqli_stmt_close($stmt);

        $stock_sql = $item_type === "pet"
            ? "UPDATE pets SET stock = stock - ? WHERE pet_id = ?"
            : "UPDATE products SET stock = stock - ? WHERE product_id = ?";
        $stmt = mysqli_prepare($conn, $stock_sql);
        if (!$stmt) return checkout_failure($conn, "Stock could not be updated.");
        mysqli_stmt_bind_param($stmt, "ii", $quantity, $item_id);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return checkout_failure($conn, "Stock could not be updated.");
        }
        mysqli_stmt_close($stmt);
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM carts WHERE customer_id = ?");
    if (!$stmt) return checkout_failure($conn, "Cart could not be cleared.");
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return checkout_failure($conn, "Cart could not be cleared.");
    }
    mysqli_stmt_close($stmt);
    if (!mysqli_commit($conn)) return checkout_failure($conn, "Order could not be committed.");
    return ["order_id" => $order_id, "error" => ""];
}
