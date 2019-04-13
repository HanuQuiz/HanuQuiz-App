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
    bindOptionButtons($("#view-app"), { delete_redirect: page.delete_redirect });
    bindMetaButtons($("#view-app"), { delete_redirect: page.delete_redirect });

    // Table of Options in question
    $("#widget-question-options").ufTable({
        dataUrl: site.uri.public + '/api/questions/q/' + page.question_slug + '/options',
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind option creation button
    bindOptionCreationButton($("#widget-question-options"));

    // Bind options table buttons
    $("#widget-question-options").on("pagerComplete.ufTable", function () {
        bindOptionButtons($(this));
    });

    // Table of Meta in question
    $("#widget-question-meta").ufTable({
        dataUrl: site.uri.public + '/api/questions/q/' + page.question_slug + '/meta',
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindMetaDataCreationButton($("#widget-question-meta"));

    // Bind meta table buttons
    $("#widget-question-meta").on("pagerComplete.ufTable", function () {
        //bindMetaButtons($(this));
    });

});
