$(document).ready(function () {
    $('#game-play-result').hide();
    $('#game-play-cards label').click(function () {
        updateEstimatedValue($(this).find('input').val());
    });
    getPlayers();
    getStatus().then(() => {
        showResult();
    }).catch(() => {
        showError('An error occurred, result could not be shown.')
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
    // Finish the estimation
    let estimationResult;
    const request = new HttpRequest({action: 'http_request', handler: 'finish_estimation', game_id: getQueryParam("game_id")});
    request.send().then(result =>  {

        // Get all estimations from the database
        const request = new HttpRequest({action: 'http_request', handler: 'get_players', game_id: getQueryParam("game_id")});
        request.send().then(result => {
            // put the estimations into an array
            let estimates = [];
            result.data['players'].forEach(player => {
                estimates.push(parseInt(player.estimated_value));
            });

            // Calculate the result values
            estimationResult = calculateResult(estimates);

            // Save and show the result values
            saveResult(estimationResult);

            showSuccess(result.message);
        }).catch(result => {
            showError(result.responseJSON.message);
        });
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}

/**
 * Show the result of the estimation
 */
function showResult()
{
    // Hide the inputs that dont do anything anymore
    $('#game-play-estimation').hide();
    $('#game-play-estimation-finish').hide();

    // Get the game result from the database
    const request = new HttpRequest({action: 'http_request', handler: 'get_game_result', game_id: getQueryParam("game_id")});
    request.send().then(result => {
        $('#game-play-result-minimum').append(result.data['minimum'].toString());
        $('#game-play-result-maximum').append(result.data['maximum'].toString());
        $('#game-play-result-average').append(result.data['average'].toString());
        $('#game-play-result-most-picked').append(result.data['most'].toString());

        $('#game-play-result').show();
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}

/**
 * Calculates the result values (min, max, avg, most picked) when given an array of numbers
 * @param estimates * number array
 * @returns {{most: string, average: number, maximum: number, minimum: number}} * object
 */
function calculateResult(estimates) {
    let minimum = 0, maximum = 0, average = 0, most = 0, mostCount = 0, count = {};

    // Calculate the min and max values
    minimum = Math.min(...estimates);
    maximum = Math.max(...estimates);

    // Calculate the average value
    for(let i = 0; i < estimates.length; i++) {
        average += estimates[i];
    }
    average = Math.round(average / estimates.length);

    // count the occurrences of each estimation value and find the most picked card
    estimates.forEach(function(i) { count[i] = (count[i] || 0) + 1;});
    mostCount = Math.max(...Object.values(count));
    most = Object.keys(count).find(key => count[key] === mostCount);

    return { "minimum": minimum, "maximum": maximum, "average": average, "most": most };

}

/**
 * Saves a result object to the database
 * @param result * object with all results
 */
function saveResult(result) {
    const request = new HttpRequest({
        action: 'http_request',
        handler: 'save_game_result',
        game_id: getQueryParam("game_id"),
        minimum: result.minimum,
        maximum: result.maximum,
        average: result.average,
        most: result.most});
    request.send().then(result => {
        showSuccess(result.message);
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}

/**
 * Closes (deletes) the game and redirects the user
 */
function closeGame()
{
    const request = new HttpRequest({action: 'http_request', handler: 'close_game', game_id: getQueryParam("game_id")});
    request.send().then(result => {
        window.location.href = "/game-overview";
    }).catch(result => {
        showError(result.responseJSON.message);
    });
}
