/**
 * Page-specific Javascript file.  Should generally be included as a separate asset bundle in your page template.
 * example: {{ assets.js('js/pages/sign-in-or-register') | raw }}
 *
 * This script depends on widgets/users.js, uf-table.js, moment.js, handlebars-helpers.js
 *
 * Target page: /apps
 */

$(document).ready(function() {
    // Set up table of apps
    $("#widget-apps").ufTable({
        dataUrl: site.uri.public + "/api/apps",
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindAppCreationButton($("#widget-apps"));

    // Bind table buttons
    $("#widget-apps").on("pagerComplete.ufTable", function () {
        bindAppButtons($(this));
    });
});
