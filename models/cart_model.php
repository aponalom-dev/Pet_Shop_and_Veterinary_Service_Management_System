<?php

function available_item_stock($conn, $item_type, $item_id) {
    if ($item_type === "pet") {
        $sql = "SELECT stock FROM pets WHERE pet_id = ? AND status = 'Available' LIMIT 1";
    } else {
        $sql = "SELECT stock FROM products WHERE product_id = ? AND status = 'Available' LIMIT 1";
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ? (int) $row["stock"] : null;
}

function cart_item_quantity($conn, $customer_id, $item_type, $item_id) {
    $sql = "SELECT quantity FROM carts WHERE customer_id = ? AND item_type = ? AND item_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isi", $customer_id, $item_type, $item_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row ? (int) $row["quantity"] : 0;
}

function add_cart_item($conn, $customer_id, $item_type, $item_id) {
    $sql = "INSERT INTO carts (customer_id, item_type, item_id, quantity) VALUES (?, ?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isi", $customer_id, $item_type, $item_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $saved;
}

function remove_cart_item($conn, $customer_id, $cart_id) {
    $sql = "DELETE FROM carts WHERE cart_id = ? AND customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $cart_id, $customer_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $saved;
}

function clear_customer_cart($conn, $customer_id) {
    $sql = "DELETE FROM carts WHERE customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $saved;
}

function list_customer_cart_items($conn, $customer_id) {
    $sql = "SELECT c.cart_id, c.item_type, c.item_id, c.quantity, p.pet_name AS item_name, p.price AS item_price FROM carts c JOIN pets p ON c.item_type = 'pet' AND c.item_id = p.pet_id WHERE c.customer_id = ? UNION ALL SELECT c.cart_id, c.item_type, c.item_id, c.quantity, pr.product_name AS item_name, pr.price AS item_price FROM carts c JOIN products pr ON c.item_type = 'product' AND c.item_id = pr.product_id WHERE c.customer_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $customer_id, $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = [];
    while ($item = mysqli_fetch_assoc($result)) {
        $item["subtotal"] = $item["item_price"] * $item["quantity"];
        $items[] = $item;
    }
    mysqli_stmt_close($stmt);
    return $items;
}
