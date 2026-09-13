<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo htmlspecialchars(role_base_url('customer')); ?>">
    <?php require __DIR__ . '/../partials/meta.php'; ?>
    <title>Secure Checkout | PawCare</title>
    <link rel="stylesheet" href="../assets/css/billing.css">
</head>

<body>

<?php if (!empty($order_error)): ?>
    <div class="checkout-error-toast">
        <?php echo htmlspecialchars($order_error); ?>
    </div>
<?php endif; ?>

<div class="checkout-page">

    <header class="checkout-header">
        <div class="checkout-heading">
            <h1>PREMIUM SECURE CHECKOUT</h1>
            <p>Delivery → Payment → Confirmation</p>
        </div>

        <div class="checkout-time">
            <div id="checkoutClock">00:00:00</div>
            <small id="checkoutDate">Loading date...</small>
        </div>

        <div class="checkout-customer">
            <span><?php echo htmlspecialchars($customer["username"]); ?></span>
            <div class="checkout-avatar">
                <?php echo strtoupper(substr($customer["username"], 0, 1)); ?>
            </div>
        </div>
    </header>

    <form method="POST" id="checkoutForm">
        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="Cash on Delivery">

        <main class="checkout-content">

            <section class="checkout-panel order-summary receipt-panel">
                <h2>ORDER SUMMARY</h2>

                <div class="receipt-header">
                    <h3>PAWCARE PET SHOP</h3>
                    <p>Pet Shop & Veterinary Service</p>
                    <p>Dhaka, Bangladesh</p>
                    <p>-----------------------------</p>
                </div>

                <div class="customer-order-info">
                    <p>--- PAWCARE PET SHOP ---</p>

                    <p>
                        Customer:
                        <?php echo htmlspecialchars($customer["full_name"]); ?>
                    </p>

                    <p>
                        Phone:
                        <?php echo htmlspecialchars($customer["phone"] ?? ""); ?>
                    </p>

                    <p>
                        Email:
                        <?php echo htmlspecialchars($customer["email"]); ?>
                    </p>

                    <p>
                        Address:
                        <?php echo htmlspecialchars($customer["address"] ?? ""); ?>
                    </p>
                </div>

                <div class="order-table-wrapper">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>ITEM</th>
                                <th>QTY</th>
                                <th>PRICE</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item["item_name"]); ?></td>
                                <td><?php echo (int) $item["quantity"]; ?>x</td>
                                <td>৳ <?php echo number_format($item["subtotal"], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="order-totals">
                    <div>
                        <span>SUBTOTAL:</span>
                        <strong>৳ <?php echo number_format($subtotal, 2); ?></strong>
                    </div>

                    <div>
                        <span>DISCOUNT:</span>
                        <strong>৳ <?php echo number_format($discount, 2); ?></strong>
                    </div>

                    <div class="grand-total">
                        <span>TOTAL:</span>
                        <strong>৳ <?php echo number_format($grand_total, 2); ?></strong>
                    </div>
                </div>
            </section>

            <section class="checkout-panel">
                <h2>🚚 DELIVERY METHOD</h2>

                <div class="delivery-options">
                    <label class="checkout-option">
                        <input type="radio" name="delivery_method" id="selectedPaymentMethod" value="Pathao Fast" checked>
                        <span>Pathao Fast</span>
                    </label>

                    <label class="checkout-option">
                        <input type="radio" name="delivery_method" value="PetPanda Go">
                        <span>PetPanda Go</span>
                    </label>

                    <label class="checkout-option">
                        <input type="radio" name="delivery_method" value="Speed Fast">
                        <span>Speed Fast</span>
                    </label>

                    <label class="checkout-option">
                        <input type="radio" name="delivery_method" value="Jhinku BD">
                        <span>Jhinku BD</span>
                    </label>

                    <label class="checkout-option">
                        <input type="radio" name="delivery_method" value="Shop Pickup">
                        <span>Shop Pickup</span>
                    </label>
                </div>

                <div class="buy-more-box">
                    <p>Want to add something else for your pet?</p>
                    <a href="<?php echo htmlspecialchars(route_url("customer/dashboard")); ?>">BUY MORE ITEMS</a>
                </div>
            </section>

            <section class="checkout-panel">
                <h2>🔒 SECURE PAYMENT</h2>

                <div class="payment-options">
                    <button type="button" class="payment-btn" data-payment="bKash">Pay with bKash</button>
                    <button type="button" class="payment-btn" data-payment="Nagad">Pay with Nagad</button>
                    <button type="button" class="payment-btn" data-payment="Rocket">Pay with Rocket</button>
                    <button type="button" class="payment-btn" data-payment="Credit Card">Pay with Credit Card</button>
                    <button type="button" class="payment-btn active" data-payment="Cash on Delivery">Pay with Cash on Delivery</button>
                </div>
            </section>

        </main>

        <footer class="checkout-footer">
            <div>
                🛡 256-bit SSL Encrypted |
                PawCare © 2026
            </div>

            <button type="submit" name="confirm_order" class="confirm-order-btn" id="confirmOrderBtn">CONFIRM ORDER</button>
        </footer>
    </form>

</div>

<div id="checkoutToast" class="checkout-toast">
    <span id="checkoutToastMessage">Selection updated.</span>
</div>

<script src="../assets/js/billing.js"></script>

</body>
</html>
