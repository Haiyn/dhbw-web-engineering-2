$(document).on("hidden.bs.modal", function () {
    document.getElementById("game-create-modal-user").value = "";
});

let users = [];
let count = 1;

const deleteCell = '<button type="button" title="Delete" onclick="removeUser(this)" class="btn btn-danger">Delete</button>';

function addUser() {
    // TODO: ajax request
    const value = document.getElementById("game-create-modal-user").value;
    const table = document.getElementById("game-create-invite-table").getElementsByTagName("tbody")[0];
    const row = table.insertRow();
    const cell1 = row.insertCell(0);
    const cell2 = row.insertCell(1);
    const cell3 = row.insertCell(2);
    cell1.innerHTML = count.toString();
    cell2.innerHTML = value;
    cell3.innerHTML = deleteCell;
    users.push({index: count, name: value});
    count++;
}

function removeUser(row) {
    // TODO: ajax request
    const rowIndex = row.parentNode.parentNode.rowIndex;
    users.splice(rowIndex - 1, 1);
    users.forEach((user, index) => {
        users[index].index = index + 1;
    });
    const newTable = document.createElement('tbody');
    const oldTable = document.getElementById("game-create-invite-table").getElementsByTagName("tbody")[0];
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
}
