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
    //bindOptionButtons($("#view-app"), { delete_redirect: page.delete_redirect });
    //bindMetaButtons($("#view-app"), { delete_redirect: page.delete_redirect });

    // Table of Options in question
    $("#widget-question-list").ufTable({
        dataUrl: site.uri.public + '/api/quiz/q/' + page.quiz_slug + '/questions',
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind option creation button
    //bindQuestionAssignButton($("#widget-question-list"));

    // Bind question list table buttons
    $("#widget-question-options").on("pagerComplete.ufTable", function () {
        bindQuestionListButtons($(this));
    });

    // Table of Meta in quiz
    $("#widget-quiz-meta").ufTable({
        dataUrl: site.uri.public + '/api/quiz/q/' + page.quiz_slug + '/meta',
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindMetaDataCreationButton($("#widget-quiz-meta"));

    // Bind meta table buttons
    $("#widget-quiz-meta").on("pagerComplete.ufTable", function () {
        bindMetaButtons($(this));
    });

});
