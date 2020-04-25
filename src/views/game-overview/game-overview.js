$(document).ready(function () {
    $('#game-overview-search').on('keyup', function () {
        // Get the search value and put it to lower case
        const value = $(this).val().toLowerCase();
        // Filter all cards with the game-overview-filter class
        $('.game-overview-filter').filter(function () {
            // Get the title of the card
            const title = this.querySelector('#game-overview-card-title');
            if (title != null) {
                // Toggle the visibility of the card based on the match of the title/ location
                // and the searched value
                const toggle = $(title).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(toggle);
            }
        });
    });
});
