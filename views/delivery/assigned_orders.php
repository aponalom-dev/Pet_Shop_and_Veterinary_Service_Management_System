<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('delivery')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Assigned Orders | PawCare</title>
    <link rel="stylesheet" href="../assets/css/delivery_assigned_orders.css">
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

            <a href="<?php echo htmlspecialchars(route_url("delivery/dashboard")); ?>">
                Dashboard
            </a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="active">
                Assigned Orders
            </a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/history")); ?>">
                History
            </a>

            <a href="<?php echo htmlspecialchars(route_url("delivery/profile")); ?>">
                Profile Settings
            </a>

        </nav>

        <div class="sidebar-logout">

            <a href="<?php echo htmlspecialchars(route_url("logout")); ?>">
                Logout
            </a>

        </div>

    </aside>

    <div class="main-area">

        <header class="topbar">

            <div>

                <h3>
                    WELCOME BACK, <?php echo strtoupper(htmlspecialchars($agent_name)); ?>!
                </h3>

                <p>Ready for today's deliveries?</p>

            </div>

            <div class="topbar-time">

                <span id="currentDate"></span>
                <span id="currentTime"></span>

            </div>

        </header>

        <main class="content">

            <div class="page-heading">

                <div>

                    <h1>Assigned Orders</h1>

                    <p>
                        Manage and update your assigned deliveries.
                    </p>

                </div>

                <div class="company-badge">
                    <?php echo htmlspecialchars($company_name); ?>
                </div>

            </div>

            <?php if ($message === "started"): ?>

                <div class="success-message">
                    Delivery started successfully.
                </div>

            <?php elseif ($message === "delivered"): ?>

                <div class="success-message">
                    Order delivered successfully.
                </div>

            <?php elseif ($message === "error"): ?>

                <div class="error-message">
                    Something went wrong. Please try again.
                </div>

            <?php elseif ($message === "invalid"): ?>

                <div class="error-message">
                    Invalid delivery request.
                </div>

            <?php endif; ?>

            <form method="GET" action="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="filter-box" id="deliveryFilter" data-search-url="<?php echo htmlspecialchars(route_url("ajax", "action=search_deliveries")); ?>" data-details-url="<?php echo htmlspecialchars(route_url("delivery/order_details")); ?>" data-action-url="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>">
                <input type="hidden" name="page" value="delivery/assigned_orders">

                <input type="text" name="search" id="deliverySearch" placeholder="Search Order ID / Customer..." value="<?php echo htmlspecialchars($search); ?>">

                <select name="status" id="deliveryStatus">

                    <option value="">All Statuses</option>

                    <option value="Assigned" <?php if ($status === "Assigned") echo "selected"; ?>>
                        Assigned
                    </option>

                    <option value="Out for Delivery" <?php if ($status === "Out for Delivery") echo "selected"; ?>>
                        Out for Delivery
                    </option>

                    <option value="Delivered" <?php if ($status === "Delivered") echo "selected"; ?>>
                        Delivered
                    </option>

                    <option value="Failed" <?php if ($status === "Failed") echo "selected"; ?>>
                        Failed
                    </option>

                    <option value="Cancelled" <?php if ($status === "Cancelled") echo "selected"; ?>>
                        Cancelled
                    </option>

                </select>

                <button type="submit" class="filter-btn">
                    SEARCH
                </button>

                <a href="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="reset-btn">
                    RESET
                </a>

            </form>

            <section class="orders-panel">

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>
                                <th>ORDER</th>
                                <th>CUSTOMER</th>
                                <th>ADDRESS</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>

                        </thead>

                        <tbody id="assignedOrderRows">

                        <?php if (count($orders) > 0): ?>

                            <?php foreach ($orders as $order): ?>

                                <tr>

                                    <td>

                                        <div class="order-id">
                                            #<?php echo (int)$order["order_id"]; ?>
                                        </div>

                                        <span class="order-date">
                                            <?php echo date("d M Y", strtotime($order["order_date"])); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <div class="customer-name">
                                            <?php echo htmlspecialchars($order["customer_name"]); ?>
                                        </div>

                                        <span class="customer-phone">
                                            <?php echo htmlspecialchars($order["customer_phone"] ?? ""); ?>
                                        </span>

                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($order["delivery_address"]); ?>
                                    </td>

                                    <td>

                                        <div class="amount">
                                            <?php echo number_format($order["total_amount"], 2); ?> BDT
                                        </div>

                                        <span class="payment-status">
                                            <?php echo htmlspecialchars($order["payment_status"]); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <?php
                                        $status_class = "status-default";

                                        if ($order["delivery_status"] === "Assigned") {
                                            $status_class = "status-assigned";
                                        } elseif ($order["delivery_status"] === "Out for Delivery") {
                                            $status_class = "status-out";
                                        } elseif ($order["delivery_status"] === "Delivered") {
                                            $status_class = "status-delivered";
                                        } elseif ($order["delivery_status"] === "Failed") {
                                            $status_class = "status-failed";
                                        } elseif ($order["delivery_status"] === "Cancelled") {
                                            $status_class = "status-cancelled";
                                        }
                                        ?>

                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($order["delivery_status"]); ?>
                                        </span>

                                    </td>

                                    <td>
                                        <a href="<?php echo htmlspecialchars(route_url("delivery/order_details")); ?>&amp;id=<?php echo (int)$order["delivery_id"]; ?>" class="details-btn">
                                                VIEW DETAILS
                                            </a>
                                        <?php if ($order["delivery_status"] === "Assigned"): ?>

                                            <form method="POST" action="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="action-form">

                                                <input type="hidden" name="delivery_id" value="<?php echo (int)$order["delivery_id"]; ?>">

                                                <button type="submit" name="start_delivery" class="action-btn">
                                                    START DELIVERY
                                                </button>

                                            </form>

                                        <?php elseif ($order["delivery_status"] === "Out for Delivery"): ?>

                                            <form method="POST" action="<?php echo htmlspecialchars(route_url("delivery/assigned_orders")); ?>" class="action-form">

                                                <input type="hidden" name="delivery_id" value="<?php echo (int)$order["delivery_id"]; ?>">

                                                <button type="submit" name="mark_delivered" class="action-btn">
                                                    MARK DELIVERED
                                                </button>

                                            </form>

                                        <?php elseif ($order["delivery_status"] === "Delivered"): ?>

                                            <span class="completed-text">
                                                COMPLETED
                                            </span>

                                        <?php elseif ($order["delivery_status"] === "Failed"): ?>

                                            <span class="failed-text">
                                                FAILED
                                            </span>

                                        <?php elseif ($order["delivery_status"] === "Cancelled"): ?>

                                            <span class="cancelled-text">
                                                CANCELLED
                                            </span>

                                        <?php else: ?>

                                            <span>-</span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="6" class="empty-data">

                                    <?php if ($search !== "" || $status !== ""): ?>
                                        No matching orders found.
                                    <?php else: ?>
                                        No assigned orders found.
                                    <?php endif; ?>

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

<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/delivery-orders.js"></script>

</body>

</html>
