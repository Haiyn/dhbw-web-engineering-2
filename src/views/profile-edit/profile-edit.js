$(document).ready(function() {
    // Enables form, shows submit button and hides enable button
    $( "#js-personal-enable" ).on( "click", function() {
        console.log("Yup");
        document.getElementById("js-personal-enable").setAttribute("hidden", null);
        document.getElementById("js-personal-submit").toggleAttribute("hidden");
        $("#js-personal-form :input").prop("disabled", false);
    });

    // Enables form, shows submit button and hides enable button
    $( "#js-password-enable" ).on( "click", function() {
        document.getElementById("js-password-enable").setAttribute("hidden", null);
        document.getElementById("js-password-submit").removeAttribute("hidden");
        $("#js-password-form :input").prop("disabled", false);
    });
});