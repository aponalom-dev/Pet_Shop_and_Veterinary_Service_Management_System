<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/catalog_model.php';
require_once __DIR__ . '/../models/cart_model.php';

function customer_dashboard_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }
    $customer_name = $_SESSION["full_name"] ?? "Customer";
    $username = $_SESSION["username"] ?? "customer";
    $customer_id = (int) $_SESSION["user_id"];
    $cart_message = $_SESSION["cart_message"] ?? "";
    $cart_message_type = $_SESSION["cart_message_type"] ?? "";
    unset($_SESSION["cart_message"]);
    unset($_SESSION["cart_message_type"]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $action = $_POST["action"] ?? "";
        if ($action === "add") {
            $item_type = $_POST["item_type"] ?? "";
            $item_id = (int) ($_POST["item_id"] ?? 0);
            if (!in_array($item_type, ["pet", "product"], true) || $item_id <= 0) {
                $_SESSION["cart_message"] = "Invalid item selected.";
                $_SESSION["cart_message_type"] = "error";
            } else {
                $available_stock = available_item_stock($conn, $item_type, $item_id);
                $current_quantity = cart_item_quantity($conn, $customer_id, $item_type, $item_id);
                if ($available_stock === null) {
                    $_SESSION["cart_message"] = "This item is currently unavailable.";
                    $_SESSION["cart_message_type"] = "error";
                } elseif ($available_stock <= 0 || $current_quantity >= $available_stock) {
                    $_SESSION["cart_message"] = "Cannot add more. Available stock limit reached.";
                    $_SESSION["cart_message_type"] = "error";
                } elseif (add_cart_item($conn, $customer_id, $item_type, $item_id)) {
                    $_SESSION["cart_message"] = "Item added to bill successfully.";
                    $_SESSION["cart_message_type"] = "success";
                } else {
                    $_SESSION["cart_message"] = "Item could not be added.";
                    $_SESSION["cart_message_type"] = "error";
                }
            }
            header("Location: " . route_url("customer/dashboard"));
            exit;
        }
        if ($action === "remove") {
            $cart_id = (int) ($_POST["cart_id"] ?? 0);
            if ($cart_id > 0) remove_cart_item($conn, $customer_id, $cart_id);
            header("Location: " . route_url("customer/dashboard"));
            exit;
        }
        if ($action === "clear") {
            clear_customer_cart($conn, $customer_id);
            header("Location: " . route_url("customer/dashboard"));
            exit;
        }
    }

    $pets = list_available_pets($conn);
    $products = list_available_products($conn);
    $cart_items = list_customer_cart_items($conn, $customer_id);
    $cart_total = 0;
    foreach ($cart_items as $item) $cart_total += $item["subtotal"];
    $best_sellers = list_best_sellers($conn);
    require __DIR__ . '/../views/customer/dashboard.php';
}
