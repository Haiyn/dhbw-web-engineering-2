$(document).ready(function () {
    hideSuccess();
    hideError();
});

function showSuccess(message)
{
    hideError();
    $('#alert-success-message').text(message);
    $('#alert-success').show();
}

function hideSuccess()
{
    $('#alert-success').hide();
}

function showError(message)
{
    hideSuccess();
    $('#alert-error-message').show().text(message);
    $('#alert-error').show();
}

function hideError()
{
    $('#alert-error').hide();
}
