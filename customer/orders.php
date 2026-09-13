<?php
require_once "../includes/session.php";
require_once "../config/database.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}
$customerId = (int) $_SESSION["user_id"];
$stmt = mysqli_prepare($conn, "SELECT order_id, total_amount, order_status FROM orders WHERE customer_id = ? ORDER BY order_id DESC");
mysqli_stmt_bind_param($stmt, "i", $customerId);
mysqli_stmt_execute($stmt);
$orders = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>My Orders | PawCare</title></head>
<body>
    <main>
        <h1>My Orders</h1>
        <?php while ($order = mysqli_fetch_assoc($orders)): ?>
            <article>
                <h2>Order #<?php echo (int) $order["order_id"]; ?></h2>
                <p><?php echo htmlspecialchars($order["order_status"]); ?></p>
                <p><?php echo number_format($order["total_amount"], 2); ?> BDT</p>
            </article>
        <?php endwhile; ?>
    </main>
</body>
</html>
