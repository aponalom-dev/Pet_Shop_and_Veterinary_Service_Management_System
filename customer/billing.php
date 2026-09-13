<?php
require_once "../includes/session.php";
require_once "../config/database.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}
$customerId = (int) $_SESSION["user_id"];
$sql = "SELECT c.quantity, p.price FROM carts c JOIN products p ON c.item_type = 'product' AND c.item_id = p.product_id WHERE c.customer_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $customerId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = 0;
while ($item = mysqli_fetch_assoc($result)) {
    $total += (int) $item["quantity"] * (float) $item["price"];
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Checkout | PawCare</title></head>
<body>
    <main>
        <h1>Checkout</h1>
        <p>Product total: <?php echo number_format($total, 2); ?> BDT</p>
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
