const accountList = document.getElementById("account-list");

if (accountList) {
    const accountSearch = document.getElementById("accountSearch");
    const accountRoleFilter = document.getElementById("accountRoleFilter");
    const actionUrl = accountList.dataset.actionUrl;
    const csrfToken = accountList.dataset.csrfToken;
    const selfId = Number(accountList.dataset.selfId);

    function accountRow(user, index) {
        const id = Number(user.user_id) || 0;
        const active = user.status === "active";
        const statusWord = active ? "Active" : "Suspended";
        let actions = '<a class="account-action edit" href="' + esc(actionUrl) + '&amp;edit=' + id + '">Edit</a>';

        if (id !== selfId) {
            actions += '<form method="POST" action="' + esc(actionUrl) + '" data-confirm="' + (active ? 'Suspend this account?' : 'Reactivate this account?') + '">' +
                '<input type="hidden" name="csrf_token" value="' + esc(csrfToken) + '">' +
                '<input type="hidden" name="account_id" value="' + id + '">' +
                '<button type="submit" name="set_status" value="1" class="account-action ' + (active ? 'suspend' : 'activate') + '">' + (active ? 'Suspend' : 'Activate') + '</button></form>' +
                '<form method="POST" action="' + esc(actionUrl) + '" data-confirm="Delete this account permanently? Related records may also be removed.">' +
                '<input type="hidden" name="csrf_token" value="' + esc(csrfToken) + '">' +
                '<input type="hidden" name="account_id" value="' + id + '">' +
                '<button type="submit" name="delete_account" value="1" class="account-action delete">Delete</button></form>';
        } else {
            actions += '<span class="account-self">Current account</span>';
        }

        return '<tr><td>' + (index + 1) + '</td>' +
            '<td>' + esc(user.full_name) + '</td>' +
            '<td>' + esc(user.username) + '</td>' +
            '<td>' + esc(user.email) + '</td>' +
            '<td>' + esc(user.phone) + '</td>' +
            '<td><span class="account-pill role-' + esc(user.role) + '">' + esc(user.role.charAt(0).toUpperCase() + user.role.slice(1)) + '</span></td>' +
            '<td><span class="account-pill status-' + esc(user.status) + '">' + statusWord + '</span></td>' +
            '<td><div class="account-row-actions">' + actions + '</div></td></tr>';
    }

    function runAccountSearch() {
        ajaxTable({
            url: accountList.dataset.searchUrl + '&role=' + encodeURIComponent(accountRoleFilter.value) + '&q=' + encodeURIComponent(accountSearch.value.trim()),
            tbody: "accountTable",
            counter: "accountCount",
            columns: 8,
            word: "accounts",
            row: accountRow
        });
    }

    liveSearch("accountSearch", runAccountSearch);
    accountRoleFilter.addEventListener("change", runAccountSearch);
    accountList.addEventListener("submit", function (event) {
        const message = event.target.dataset.confirm;
        if (message && !confirm(message)) event.preventDefault();
    });
}
