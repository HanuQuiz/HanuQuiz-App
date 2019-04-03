/**
 * Page-specific Javascript file.  Should generally be included as a separate asset bundle in your page template.
 * example: {{ assets.js('js/pages/sign-in-or-register') | raw }}
 *
 * This script depends on widgets/questions.js, uf-table.js, moment.js, handlebars-helpers.js
 *
 * Target page: /questions
 */

$(document).ready(function() {
    // Set up table of Questions
    $("#widget-questions").ufTable({
        dataUrl: site.uri.public + "/api/questions",
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindQuestionCreationButton($("#widget-questions"));

    // Bind table buttons
    $("#widget-questions").on("pagerComplete.ufTable", function () {
        bindQuestionButtons($(this));
    });
});
