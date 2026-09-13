<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('delivery')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Delivery History | PawCare</title>
    <link rel="stylesheet" href="../assets/css/delivery_history.css">
</head>

<body>

<div class="delivery-layout">

    <aside class="sidebar">

        <div class="sidebar-brand">

            <img src="../assets/images/petLogo.jpeg" alt="PawCare Logo">

            <h2>PAWCARE</h2>

            <p><?php echo htmlspecialchars($company_name); ?></p>

        </div>

        <nav class="sidebar-menu">

            <a href="<?php echo htmlspecialchars(route_url("delivery/dashboard")); ?>">Dashboard</a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>">Assigned Orders</a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/history")); ?>" class="active">History</a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/profile")); ?>">
                Profile Settings
            </a>

        </nav>

        <div class="sidebar-logout">
            <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">Logout</a>
        </div>

    </aside>

    <div class="main-area">

        <header class="topbar">

            <div>

                <h3>
                    WELCOME BACK, <?php echo strtoupper(htmlspecialchars($agent_name)); ?>!
                </h3>

                <p>Review your previous delivery activity.</p>

            </div>

            <div class="topbar-time">

                <span id="currentDate"></span>
                <span id="currentTime"></span>

            </div>

        </header>

        <main class="content">

            <div class="page-heading">

                <div>

                    <h1>Delivery History</h1>

                    <p>
                        View completed, failed and cancelled deliveries.
                    </p>

                </div>

                <div class="company-badge">
                    <?php echo htmlspecialchars($company_name); ?>
                </div>

            </div>

            <section class="history-panel">

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>
                                <th>ORDER</th>
                                <th>CUSTOMER</th>
                                <th>ADDRESS</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>DELIVERED AT</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php if (count($history_rows) > 0): ?>

                            <?php foreach ($history_rows as $history): ?>

                                <tr>

                                    <td>

                                        <div class="order-id">
                                            #<?php echo (int)$history["order_id"]; ?>
                                        </div>

                                        <span class="small-text">
                                            <?php echo date("d M Y", strtotime($history["order_date"])); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <div class="customer-name">
                                            <?php echo htmlspecialchars($history["customer_name"]); ?>
                                        </div>

                                        <span class="small-text">
                                            <?php echo htmlspecialchars($history["customer_phone"] ?? ""); ?>
                                        </span>

                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($history["delivery_address"]); ?>
                                    </td>

                                    <td>

                                        <div class="amount">
                                            <?php echo number_format($history["total_amount"], 2); ?> BDT
                                        </div>

                                    </td>

                                    <td>

                                        <?php
                                        $status_class = "status-default";

                                        if ($history["delivery_status"] === "Delivered") {
                                            $status_class = "status-delivered";
                                        } elseif ($history["delivery_status"] === "Failed") {
                                            $status_class = "status-failed";
                                        } elseif ($history["delivery_status"] === "Cancelled") {
                                            $status_class = "status-cancelled";
                                        }
                                        ?>

                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($history["delivery_status"]); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <?php if (!empty($history["delivered_at"])): ?>

                                            <?php echo date("d M Y, h:i A", strtotime($history["delivered_at"])); ?>

                                        <?php else: ?>

                                            <span class="not-available">N/A</span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="6" class="empty-data">
                                    No delivery history found.
                                </td>

                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</div>

<footer class="delivery-footer">
    POWERFUL PET MANAGEMENT ENGINE V2.0 LIVE | SYSTEM SECURED
</footer>

<script>

function updateDateTime() {

    const now = new Date();

    document.getElementById("currentDate").textContent = now.toLocaleDateString("en-US", {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric"
    });

    document.getElementById("currentTime").textContent = now.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit"
    });
}

updateDateTime();
setInterval(updateDateTime, 1000);

</script>

</body>

</html>
