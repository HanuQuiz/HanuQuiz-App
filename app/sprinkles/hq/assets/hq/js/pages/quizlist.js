/**
 * Page-specific Javascript file.  Should generally be included as a separate asset bundle in your page template.
 * example: {{ assets.js('js/pages/sign-in-or-register') | raw }}
 *
 * This script depends on widgets/quizlist.js, uf-table.js, moment.js, handlebars-helpers.js
 *
 * Target page: /questions
 */

$(document).ready(function() {
    // Set up table of Questions
    $("#widget-quiz").ufTable({
        dataUrl: site.uri.public + "/api/quiz",
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindQuizCreationButton($("#widget-quiz"));

    // Bind table buttons
    $("#widget-quiz").on("pagerComplete.ufTable", function () {
        bindQuizButtons($(this));
    });
});
