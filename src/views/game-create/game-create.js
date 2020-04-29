$(document).on("hidden.bs.modal", function () {
    $("#game-create-modal-user").val("");
});

let users = [];
let userIds = [];
let count = 1;

const deleteCell = '<button type="button" title="Delete" onclick="removeUser(this)" class="btn btn-danger">Delete</button>';

/**
 * Add a user after checking the name of the user with a http request
 */
function addUser()
{
    const name = $("#game-create-modal-user").val();
    const request = new HttpRequest({action: 'http_request', handler: 'user_access', user_name: name, user_ids: userIds});
    request.send().then(result => {
            userIds.push(result.data['user_id']);
            addRow(name);
            showSuccess(result.message);
        }).catch(result => {
            showError(result.responseJSON.message);
        });
}

/**
 * Add a row with a user name to the table
 * @param name Name of the user of the row
 */
function addRow(name)
{
    const table = $("#game-create-invite-table tbody")[0];
    const row = table.insertRow();
    const cell1 = row.insertCell(0);
    const cell2 = row.insertCell(1);
    const cell3 = row.insertCell(2);
    cell1.innerHTML = count.toString();
    cell2.innerHTML = name;
    cell3.innerHTML = deleteCell;
    users.push({index: count, name: name});
    count++;
    $("#game-create-users").val(JSON.stringify(users));
}

/**
 * Remove a user at a specific row and update all indices
 * @param row Row of the user to be removed
 */
function removeUser(row)
{
    const rowIndex = row.parentNode.parentNode.rowIndex;
    users.splice(rowIndex - 1, 1);
    userIds.splice(rowIndex - 1, 1);
    users.forEach((user, index) => {
        user.index = index + 1;
    });
    const newTable = document.createElement('tbody');
    const oldTable = $("#game-create-invite-table tbody")[0];
    users.forEach(user => {
        const row = newTable.insertRow();
        const cell1 = row.insertCell(0);
        const cell2 = row.insertCell(1);
        const cell3 = row.insertCell(2);
        cell1.innerHTML = user.index.toString();
        cell2.innerHTML = user.name;
        cell3.innerHTML = deleteCell;
    });
    oldTable.parentNode.replaceChild(newTable, oldTable);
    count--;
    $("#game-create-users").val(JSON.stringify(users));
    showSuccess("User successfully deleted.");
}
