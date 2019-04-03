/**
 * Page-specific Javascript file.  Should generally be included as a separate asset bundle in your page template.
 * example: {{ assets.js('js/pages/sign-in-or-register') | raw }}
 *
 * This script depends on uf-table.js, moment.js, handlebars-helpers.js
 *
 * Target page: /apps/app/{slug}
 */

$(document).ready(function() {
    // Control buttons
    bindAppButtons($("#view-app"), { delete_redirect: page.delete_redirect });

    // Table of users in this App
    $("#widget-app-moderators").ufTable({
        dataUrl: site.uri.public + '/api/apps/app/' + page.app_slug + '/moderators',
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind user table buttons
    $("#widget-app-moderators").on("pagerComplete.ufTable", function () {
        bindUserButtons($(this));
    });
});
