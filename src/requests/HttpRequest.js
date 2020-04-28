/**
 * Class HttpRequest
 * Creates a http request.
 * The data, for example {action: game_create, handler: user_access} must be given.
 * @package requests
 */
class HttpRequest {
    constructor(data)
    {
        this.data = data;
    }

    /**
     * Send the http request
     * @returns {Promise<Array>} Message and data of the result
     */
    send()
    {
        return new Promise((resolve, reject) => $.ajax({
            url: window.location.origin + window.location.pathname,
            accepts: {json: 'application/json'},
            dataType: 'json',
            method: 'POST',
            data: this.data,
            success: (result) => {
                // Get message with: result.message
                // Get data with: result.data
                resolve(result);
            },
            error: (result) => {
                // Get message with: result.responseJSON.message
                // Get data with: result.responseJSON.data
                reject(result);
            }
        }));
    }
}
