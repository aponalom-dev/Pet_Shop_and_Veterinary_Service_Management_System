<?php
require_once "../includes/session.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Checkout | PawCare</title></head>
<body>
    <main>
        <h1>Checkout</h1>
        <form method="post">
            <label>Delivery Method
                <select name="delivery_method" required>
                    <option value="Shop Pickup">Shop Pickup</option>
                    <option value="Pathao Fast">Pathao Fast</option>
                </select>
            </label>
            <label>Payment Method
                <select name="payment_method" required>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="bKash">bKash</option>
                </select>
            </label>
            <button type="submit" name="confirm_order">Confirm Order</button>
        </form>
    </main>
</body>
</html>
