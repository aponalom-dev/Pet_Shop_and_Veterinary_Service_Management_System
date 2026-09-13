<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/admin_model.php';

function admin_customer_reviews_controller($conn) {
    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: " . route_url("login"));
        exit;
    }
    $success_message = "";
    $error_message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_review_status"])) {
        $review_id = (int) ($_POST["review_id"] ?? 0);
        $status = $_POST["status"] ?? "";
        if ($review_id <= 0 || !in_array($status, ["Visible", "Hidden"], true)) {
            $error_message = "Invalid review information.";
        } elseif (admin_update_review_status($conn, $review_id, $status)) {
            $success_message = "Review status updated successfully.";
        } else {
            $error_message = "Review status could not be updated.";
        }
    }
    $data = admin_reviews_data($conn);
    $reviews = $data["reviews"];
    $champion_pet = $data["champion_pet"];
    require __DIR__ . '/../views/admin/customer_reviews.php';
}
