<?php
require_once "../includes/session.php";
require_once "../config/database.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}
$orderId = (int) ($_GET["order_id"] ?? 0);
$customerId = (int) $_SESSION["user_id"];
$stmt = mysqli_prepare($conn, "SELECT order_id, total_amount, order_status FROM orders WHERE order_id = ? AND customer_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $orderId, $customerId);
mysqli_stmt_execute($stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$order) {
    header("Location: orders.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Order Details | PawCare</title></head>
<body>
    <main>
        <h1>Order #<?php echo (int) $order["order_id"]; ?></h1>
        <p>Status: <?php echo htmlspecialchars($order["order_status"]); ?></p>
        <p>Total: <?php echo number_format($order["total_amount"], 2); ?> BDT</p>
        <a href="orders.php">Back to Orders</a>
    </main>
</body>
</html>
