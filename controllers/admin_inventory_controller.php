<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/admin_model.php';

function admin_inventory_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: " . route_url("login"));
        exit;
    }

    $pets = admin_inventory_list($conn, "pet");
    $products = admin_inventory_list($conn, "product");
    $total_pet_stock = 0;
    $total_product_stock = 0;
    $low_stock = 0;
    $out_of_stock = 0;

    foreach ($pets as $pet) {
        $stock = (int) $pet["stock"];
        $total_pet_stock += $stock;
        if ($stock === 0) $out_of_stock += 1;
        elseif ($stock <= 5) $low_stock += 1;
    }
    foreach ($products as $product) {
        $stock = (int) $product["stock"];
        $total_product_stock += $stock;
        if ($stock === 0) $out_of_stock += 1;
        elseif ($stock <= 5) $low_stock += 1;
    }
    require __DIR__ . '/../views/admin/inventory.php';
}
