function updateDateTime() {
    const now = new Date();

    const date = now.toLocaleDateString("en-US", {
        weekday: "long",
        month: "short",
        day: "2-digit",
        year: "numeric"
    });

    const time = now.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    });

    document.getElementById("currentDate").textContent = date;
    document.getElementById("currentTime").textContent = time;
}

updateDateTime();
setInterval(updateDateTime, 1000);

const statGrid = document.querySelector(".stat-grid[data-stats-url]");
if (statGrid) {
    function refreshStats() {
        fetch(statGrid.dataset.statsUrl, { credentials: "same-origin" })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.error) return;
                document.querySelectorAll("[data-stat]").forEach(function (element) {
                    const key = element.dataset.stat;
                    if (data[key] === undefined) return;
                    element.textContent = key === "total_revenue"
                        ? "৳" + Number(data[key]).toLocaleString("en-BD", { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        : data[key];
                });
            })
            .catch(function () { /* Keep the server-rendered values if refresh fails. */ });
    }
    setInterval(refreshStats, 15000);
}
