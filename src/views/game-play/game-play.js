$(document).ready(function () {
    getPlayers();
    $('#game-play-cards label').click(function () {
        updateEstimatedValue($(this).find('input').val());
    });
});

let count = 1;

/**
 * Add a user after checking the name of the user with a http request
 */
function getPlayers()
{
    const request = new HttpRequest({action: 'http_request', handler: 'get_players', game_id: getQueryParam("game_id")});
    request.send().then(result => {
        generateTable(result.data['players']);
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}

function generateTable(players)
{
    if (typeof players === 'undefined' || !Array.isArray(players)) {
        return;
    }
    clearTable();
    players.forEach(player => {
        addRow(player['username'], player['estimated_value'] === "0" ? "Not voted" : "Voted");
    });
}


function clearTable()
{
    const table = $('#game-play-players-table tbody')[0];
    table.innerHTML = "";
    count = 1;
}

/**
 * Add a row with a user name to the table
 * @param name Name of the player of the row
 * @param status Status if user has estimated
 */
function addRow(name, status)
{
    const table = $("#game-play-players-table tbody")[0];
    const row = table.insertRow();
    const cell1 = row.insertCell(0);
    const cell2 = row.insertCell(1);
    const cell3 = row.insertCell(2);
    cell1.innerHTML = count.toString();
    cell2.innerHTML = name;
    cell3.innerHTML = status;
    count++;
}

function updateEstimatedValue(value)
{
    const request = new HttpRequest({
        action: 'http_request',
        handler: 'update_player',
        game_id: getQueryParam("game_id"),
        estimated_value: value});
    request.send().then(result => {
        showSuccess(result.message);
        getPlayers();
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}
