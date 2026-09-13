<?php
require_once "../includes/session.php";
require_once "../config/database.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}
$customerId = (int) $_SESSION["user_id"];
$stmt = mysqli_prepare($conn, "SELECT full_name, username, email, phone, address FROM users WHERE user_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $customerId);
mysqli_stmt_execute($stmt);
$customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$customer) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Customer Profile | PawCare</title></head>
<body>
    <main>
        <h1><?php echo htmlspecialchars($customer["full_name"]); ?></h1>
        <p>Username: <?php echo htmlspecialchars($customer["username"]); ?></p>
        <p>Email: <?php echo htmlspecialchars($customer["email"]); ?></p>
        <p>Phone: <?php echo htmlspecialchars($customer["phone"]); ?></p>
        <p>Address: <?php echo htmlspecialchars($customer["address"]); ?></p>
        <a href="edit_profile.php">Edit Profile</a>
    </main>
</body>
</html>
