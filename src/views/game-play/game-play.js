$(document).ready(function () {
    $('#game-play-result').hide();
    $('#game-play-cards label').click(function () {
        updateEstimatedValue($(this).find('input').val());
    });
    getPlayers();
    getStatus().then(() => {
        showResult();
    }).catch(() => {
        showResult('An error occurred, result could not be shown.')
    });
});

let count = 1;

/**
 * Get all players and update the table to show the new status
 */
function getPlayers()
{
    const request = new HttpRequest({action: 'http_request', handler: 'get_players', game_id: getQueryParam("game_id")});
    request.send().then(result => {
        generateTable(result.data['players']);
        setTimeout(getPlayers, 2000);
    }).catch(result => {
        showError(result.responseJSON.message);
        setTimeout(getPlayers, 2000);
    });
}

/**
 * Get the status of the game (running/finished) in order to show the result
 * @returns {Promise} Resolved when the status is finished
 */
function getStatus()
{
    return new Promise(function (resolve, reject) {
        (function waitForStatusFinished()
        {
            const request = new HttpRequest({action: 'http_request', handler: 'get_status', game_id: getQueryParam("game_id")});
            request.send().then(result => {
                if (result.data['status'] === 'finished') {
                    resolve();
                }
                setTimeout(waitForStatusFinished, 2000);
            }).catch(result => {
                showError(result.responseJSON.message);
                setTimeout(waitForStatusFinished, 2000);
            });
        })();
    })

}

/**
 * Generate the table
 * @param players Players to be added to the table
 */
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

/**
 * Clear the table, so that it can be generated again
 */
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

/**
 * Update the estimated value via a http request
 * @param value New estimated value
 */
function updateEstimatedValue(value)
{
    const request = new HttpRequest({
        action: 'http_request',
        handler: 'update_estimated_value',
        game_id: getQueryParam("game_id"),
        estimated_value: value});
    request.send().then(result => {
        showSuccess(result.message);
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}

/**
 * Finish the estimation, set the status to finished via a http request
 */
function finishEstimation()
{
    const request = new HttpRequest({action: 'http_request', handler: 'finish_estimation', game_id: getQueryParam("game_id")});
    request.send().then(result => {
        showSuccess(result.message);
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}

/**
 * Show the result of the estimation
 */
function showResult()
{
    $('#game-play-result').show();
}

function closeGame()
{

}
