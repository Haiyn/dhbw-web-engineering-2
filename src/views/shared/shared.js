/**
 * Get a query parameter by name
 * @param param Name of the parameter
 * @returns {string} Value of the parameter
 */
function getQueryParam(param)
{
    const url = new URL(window.location.href);
    return url.searchParams.get(param)
}
