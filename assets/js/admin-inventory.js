const tabs = document.querySelectorAll(".inventory-tab");
const panels = document.querySelectorAll(".inventory-panel");
const searchInput = document.getElementById("inventorySearch");

function inventoryStockBadge(value) {
    const stock = Number(value) || 0;
    const stockClass = stock === 0 ? "out-stock" : (stock <= 5 ? "low-stock" : "good-stock");
    return '<span class="stock-badge ' + stockClass + '">' + stock + '</span>';
}

function inventoryPrice(value) {
    return "৳" + Number(value).toLocaleString("en-BD", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function petInventoryRow(item) {
    return '<tr class="inventory-row">' +
        '<td>#' + Number(item.pet_id) + '</td>' +
        '<td>' + esc(item.pet_name) + '</td>' +
        '<td>' + esc(item.category_name || 'N/A') + '</td>' +
        '<td>' + esc(item.breed || 'N/A') + '</td>' +
        '<td>' + inventoryPrice(item.price) + '</td>' +
        '<td>' + inventoryStockBadge(item.stock) + '</td>' +
        '<td>' + esc(item.status) + '</td></tr>';
}

function productInventoryRow(item) {
    return '<tr class="inventory-row">' +
        '<td>#' + Number(item.product_id) + '</td>' +
        '<td>' + esc(item.product_name) + '</td>' +
        '<td>' + esc(item.category_name || 'N/A') + '</td>' +
        '<td>' + inventoryPrice(item.price) + '</td>' +
        '<td>' + inventoryStockBadge(item.stock) + '</td>' +
        '<td>' + esc(item.status) + '</td></tr>';
}

function runInventorySearch() {
    const activePanel = document.querySelector(".inventory-panel.active-panel");
    const isPet = activePanel.id === "petsTable";
    const type = isPet ? "pet" : "product";
    ajaxTable({
        url: searchInput.dataset.searchUrl + "&type=" + type + "&q=" + encodeURIComponent(searchInput.value.trim()),
        tbody: isPet ? "petInventoryRows" : "productInventoryRows",
        counter: isPet ? "petInventoryCount" : "productInventoryCount",
        columns: isPet ? 7 : 6,
        word: "Records",
        row: isPet ? petInventoryRow : productInventoryRow
    });
}

tabs.forEach(function (tab) {
    tab.addEventListener("click", function () {
        tabs.forEach(function (item) { item.classList.remove("active"); });
        panels.forEach(function (panel) { panel.classList.remove("active-panel"); });
        tab.classList.add("active");
        document.getElementById(tab.dataset.target).classList.add("active-panel");
        searchInput.value = "";
        runInventorySearch();
    });
});

liveSearch("inventorySearch", runInventorySearch);
