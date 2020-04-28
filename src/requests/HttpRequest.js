class HttpRequest {
    constructor(url, data)
    {
        this.url = url;
        this.data = data;
    }

    send()
    {
        return new Promise((resolve, reject) => $.ajax({
            url: window.location.origin + this.url,
            accepts: {json: 'application/json'},
            dataType: 'json',
            method: 'POST',
            data: this.data,
            success: (result) => {
                resolve(result);
            },
            error: (result) => {
                reject(result);
            }
        }));
    }
}
