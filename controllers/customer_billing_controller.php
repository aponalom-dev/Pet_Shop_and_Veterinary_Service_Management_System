<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/../models/cart_model.php';
require_once __DIR__ . '/../models/checkout_model.php';

function customer_billing_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "customer") {
        header("Location: " . route_url("login"));
        exit;
    }

    $customer_id = (int) $_SESSION["user_id"];
    $customer_name = $_SESSION["full_name"] ?? "Customer";
    $customer = find_customer_account($conn, $customer_id);
    $cart_items = list_customer_cart_items($conn, $customer_id);
    if (!$customer || empty($cart_items)) {
        header("Location: " . route_url("customer/dashboard"));
        exit;
    }

    $subtotal = 0;
    foreach ($cart_items as $item) $subtotal += $item["subtotal"];
    $discount = 0;
    $grand_total = $subtotal - $discount;
    $order_error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["confirm_order"])) {
        $delivery_method = $_POST["delivery_method"] ?? "";
        $payment_method = $_POST["payment_method"] ?? "";
        $allowed_delivery_methods = ["Pathao Fast", "PetPanda Go", "Speed Fast", "Jhinku BD", "Shop Pickup"];
        $allowed_payment_methods = ["bKash", "Nagad", "Rocket", "Credit Card", "Cash on Delivery"];

        if (!in_array($delivery_method, $allowed_delivery_methods, true)) {
            $order_error = "Please select a valid delivery method.";
        } elseif (!in_array($payment_method, $allowed_payment_methods, true)) {
            $order_error = "Please select a valid payment method.";
        } else {
            $result = place_customer_order($conn, $customer_id, $customer, $cart_items, $grand_total, $delivery_method, $payment_method);
            if ($result["order_id"] > 0) {
                header("Location: " . route_url("customer/order_success", "order_id=" . $result["order_id"]));
                exit;
            }
            $order_error = "Order could not be completed. " . $result["error"];
        }
    }

    require __DIR__ . '/../views/customer/billing.php';
}
