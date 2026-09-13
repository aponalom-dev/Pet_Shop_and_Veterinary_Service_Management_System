// Shared AJAX helpers following the model project's live-search/table pattern.
function esc(text) {
    return String(text === null || text === undefined ? "" : text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function liveSearch(inputId, handler) {
    const input = document.getElementById(inputId);
    if (!input) return;
    let timer;
    input.addEventListener("input", function () {
        clearTimeout(timer);
        timer = setTimeout(handler, 250);
    });
}

function ajaxTable(options) {
    const tbody = document.getElementById(options.tbody);
    const counter = document.getElementById(options.counter);
    if (!tbody) return;

    fetch(options.url, { credentials: "same-origin" })
        .then(function (response) { return response.json(); })
        .then(function (rows) {
            if (rows && rows.error) {
                tbody.innerHTML = '<tr><td colspan="' + options.columns + '" class="empty-message">' + esc(rows.error) + '</td></tr>';
                return;
            }
            if (!Array.isArray(rows) || rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="' + options.columns + '" class="empty-message">Nothing matches your search.</td></tr>';
                if (counter) counter.textContent = '0 ' + options.word;
                return;
            }
            let html = '';
            rows.forEach(function (item, index) { html += options.row(item, index); });
            tbody.innerHTML = html;
            if (counter) counter.textContent = rows.length + ' ' + options.word;
        })
        .catch(function (error) {
            console.error('Search failed:', error);
            tbody.innerHTML = '<tr><td colspan="' + options.columns + '" class="empty-message">Search is temporarily unavailable.</td></tr>';
        });
}
