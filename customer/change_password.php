<?php
require_once "../includes/session.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Change Password | PawCare</title></head>
<body>
    <main>
        <h1>Change Password</h1>
        <form method="post">
            <label>Current Password <input type="password" name="current_password" required></label>
            <label>New Password <input type="password" name="new_password" required></label>
            <label>Confirm Password <input type="password" name="confirm_password" required></label>
            <button type="submit">Update Password</button>
        </form>
    </main>
</body>
</html>
