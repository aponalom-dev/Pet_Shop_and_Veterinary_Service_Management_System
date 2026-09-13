<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/admin_model.php';

function admin_manage_inventory_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: " . route_url("login"));
        exit;
    }
    $success_message = "";
    $error_message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_inventory"])) {
        $type = $_POST["inventory_type"] ?? "";
        $item_id = (int) ($_POST["item_id"] ?? 0);
        $price = (float) ($_POST["price"] ?? 0);
        $quantity = (int) ($_POST["quantity"] ?? 0);
        if (($type !== "pet" && $type !== "product") || $item_id <= 0 || $price <= 0 || $quantity <= 0) {
            $error_message = "Please select an item and enter valid information.";
        } elseif (admin_add_inventory($conn, $type, $item_id, $price, $quantity)) {
            $success_message = "Inventory stock added successfully.";
        } else {
            $error_message = "Inventory could not be updated.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove_inventory"])) {
        $type = $_POST["remove_type"] ?? "";
        $item_id = (int) ($_POST["remove_item_id"] ?? 0);
        $quantity = (int) ($_POST["remove_quantity"] ?? 0);
        if ($type === "" || $item_id <= 0) {
            $error_message = "Please select an item from the catalog first.";
        } elseif ($quantity <= 0) {
            $error_message = "Please enter a valid remove quantity.";
        } elseif ($type !== "pet" && $type !== "product") {
            $error_message = "Invalid inventory type.";
        } elseif (admin_remove_inventory($conn, $type, $item_id, $quantity)) {
            $success_message = "Inventory stock removed successfully.";
        } else {
            $error_message = "Not enough stock available to remove.";
        }
    }

    $pets = admin_inventory_list($conn, "pet");
    $products = admin_inventory_list($conn, "product");
    require __DIR__ . '/../views/admin/manage_inventory.php';
}
