function getQueryParam(param)
{
    const url = new URL(window.location.href);
    return url.searchParams.get(param)
}
