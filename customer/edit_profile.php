<?php
require_once "../includes/session.php";
require_once "../config/database.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}
$customerId = (int) $_SESSION["user_id"];
$stmt = mysqli_prepare($conn, "SELECT full_name, email, phone, address FROM users WHERE user_id = ? LIMIT 1");
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
<head><meta charset="UTF-8"><title>Edit Profile | PawCare</title></head>
<body>
    <main>
        <h1>Edit Profile</h1>
        <form method="post">
            <label>Full Name <input name="full_name" value="<?php echo htmlspecialchars($customer["full_name"]); ?>" required></label>
            <label>Email <input type="email" name="email" value="<?php echo htmlspecialchars($customer["email"]); ?>" required></label>
            <label>Phone <input name="phone" value="<?php echo htmlspecialchars($customer["phone"]); ?>" required></label>
            <label>Address <textarea name="address" required><?php echo htmlspecialchars($customer["address"]); ?></textarea></label>
            <button type="submit">Save Changes</button>
        </form>
    </main>
</body>
</html>
