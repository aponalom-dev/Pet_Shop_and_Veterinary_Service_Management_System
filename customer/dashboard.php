<?php
require_once "../includes/session.php";
require_once "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "customer") {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION["username"] ?? "Customer";
$pets = mysqli_query($conn, "SELECT pet_name, breed, price FROM pets WHERE status = 'Available' ORDER BY pet_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | PawCare</title>
</head>
<body>
    <main>
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <h2>Available Pets</h2>
        <?php while ($pet = mysqli_fetch_assoc($pets)): ?>
            <article>
                <h3><?php echo htmlspecialchars($pet["pet_name"]); ?></h3>
                <p><?php echo htmlspecialchars($pet["breed"]); ?></p>
                <p><?php echo number_format($pet["price"], 2); ?> BDT</p>
            </article>
        <?php endwhile; ?>
    </main>
</body>
</html>
