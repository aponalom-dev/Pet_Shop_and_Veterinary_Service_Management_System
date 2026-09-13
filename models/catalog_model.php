<?php

function list_available_pets($conn) {
    $sql = "SELECT p.pet_id, p.pet_name, p.breed, p.price, p.stock, p.image, pc.category_name FROM pets p JOIN pet_categories pc ON p.category_id = pc.category_id WHERE p.status = 'Available' ORDER BY p.pet_id DESC";
    $result = mysqli_query($conn, $sql);
    $pets = [];
    while ($row = mysqli_fetch_assoc($result)) $pets[] = $row;
    return $pets;
}

function list_available_products($conn) {
    $sql = "SELECT pr.product_id, pr.product_name, pr.brand, pr.price, pr.stock, pr.image, pc.category_name FROM products pr JOIN product_categories pc ON pr.category_id = pc.category_id WHERE pr.status = 'Available' ORDER BY pr.product_id DESC";
    $result = mysqli_query($conn, $sql);
    $products = [];
    while ($row = mysqli_fetch_assoc($result)) $products[] = $row;
    return $products;
}

function list_best_sellers($conn) {
    $sql = "SELECT oi.item_type, oi.item_id, oi.item_name, SUM(oi.quantity) AS total_sold FROM order_items oi JOIN orders o ON oi.order_id = o.order_id WHERE o.order_status != 'Cancelled' GROUP BY oi.item_type, oi.item_id, oi.item_name ORDER BY total_sold DESC";
    $result = mysqli_query($conn, $sql);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) $items[] = $row;
    return $items;
}
