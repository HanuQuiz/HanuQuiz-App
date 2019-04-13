<?php
/**
 * Hanu-Quiz (https://hanu-quiz.varunverma.org)
 *
 * @link      https://projects.ayansh.com/projects/hanu-quiz-framework
 * @copyright Copyright (c) 2019 Ayansh TechnoSoft
 * @license   (MIT License)
 */

use UserFrosting\Sprinkle\Core\Util\NoCache;

/**
 * Routes for Quiz management.
 */
$app->group('/quiz', function () {
    $this->get('', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:quizList')
        ->setName('uri_quiz');

    $this->get('/q/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:pageInfo');
})->add('authGuard')->add(new NoCache());

$app->group('/api/quiz', function () {
    $this->delete('/q/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:delete');

    $this->get('', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:getList');

    $this->get('/q/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:getInfo');

    $this->get('/q/{slug}/questions', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:getQuestions');

    $this->get('/q/{slug}/meta', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:getMeta');

    $this->post('', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:create');

    $this->put('/q/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:updateInfo');
})->add('authGuard')->add(new NoCache());

$app->group('/modals/quiz', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:getModalCreate');

    $this->get('/edit', 'UserFrosting\Sprinkle\Hq\Controller\QuizController:getModalEdit');
})->add('authGuard')->add(new NoCache());