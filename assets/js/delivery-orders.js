const deliveryFilter = document.getElementById("deliveryFilter");
const deliverySearch = document.getElementById("deliverySearch");
const deliveryStatus = document.getElementById("deliveryStatus");

function deliveryOrderRow(order) {
    const deliveryId = Number(order.delivery_id);
    const status = String(order.delivery_status || "");
    const statusClasses = {
        "Assigned": "status-assigned",
        "Out for Delivery": "status-out",
        "Delivered": "status-delivered",
        "Failed": "status-failed",
        "Cancelled": "status-cancelled"
    };
    const statusClass = statusClasses[status] || "status-default";
    const detailsUrl = deliveryFilter.dataset.detailsUrl;
    const actionUrl = deliveryFilter.dataset.actionUrl;
    let action = '<a href="' + esc(detailsUrl) + '&amp;id=' + deliveryId + '" class="details-btn">VIEW DETAILS</a>';

    if (status === "Assigned" || status === "Out for Delivery") {
        const buttonName = status === "Assigned" ? "start_delivery" : "mark_delivered";
        const buttonText = status === "Assigned" ? "START DELIVERY" : "MARK DELIVERED";
        action += '<form method="POST" action="' + esc(actionUrl) + '" class="action-form">' +
            '<input type="hidden" name="delivery_id" value="' + deliveryId + '">' +
            '<button type="submit" name="' + buttonName + '" class="action-btn">' + buttonText + '</button></form>';
    } else if (status === "Delivered") {
        action += '<span class="completed-text">COMPLETED</span>';
    } else if (status === "Failed") {
        action += '<span class="failed-text">FAILED</span>';
    } else if (status === "Cancelled") {
        action += '<span class="cancelled-text">CANCELLED</span>';
    }

    return '<tr>' +
        '<td><div class="order-id">#' + Number(order.order_id) + '</div><span class="order-date">' + esc(String(order.order_date || "").slice(0, 10)) + '</span></td>' +
        '<td><div class="customer-name">' + esc(order.customer_name) + '</div><span class="customer-phone">' + esc(order.customer_phone || "") + '</span></td>' +
        '<td>' + esc(order.delivery_address) + '</td>' +
        '<td><div class="amount">' + Number(order.total_amount).toLocaleString("en-BD", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' BDT</div><span class="payment-status">' + esc(order.payment_status) + '</span></td>' +
        '<td><span class="status-badge ' + statusClass + '">' + esc(status) + '</span></td>' +
        '<td>' + action + '</td></tr>';
}

function searchDeliveries() {
    ajaxTable({
        url: deliveryFilter.dataset.searchUrl + "&q=" + encodeURIComponent(deliverySearch.value.trim()) + "&status=" + encodeURIComponent(deliveryStatus.value),
        tbody: "assignedOrderRows",
        columns: 6,
        word: "orders",
        row: deliveryOrderRow
    });
}

liveSearch("deliverySearch", searchDeliveries);
deliveryStatus.addEventListener("change", searchDeliveries);
deliveryFilter.addEventListener("submit", function (event) {
    event.preventDefault();
    searchDeliveries();
});
