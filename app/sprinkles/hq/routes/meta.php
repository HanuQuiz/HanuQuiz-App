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
 * Routes for Question Meta management.
 */
$app->group('/api/q/meta', function () {

    $this->post('', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:create');

    $this->put('/m/{id}', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:updateInfo');

})->add('authGuard')->add(new NoCache());

$app->group('/modals/q/{slug}/meta', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:getModalCreate');

    $this->get('/edit', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:getModalEdit');
})->add('authGuard')->add(new NoCache());

/**
 * Routes for Quiz Meta management.
 */
$app->group('/api/quiz/meta', function () {

    $this->post('', 'UserFrosting\Sprinkle\Hq\Controller\QuizMetaController:create');

    $this->put('/m/{id}', 'UserFrosting\Sprinkle\Hq\Controller\QuizMetaController:updateInfo');

})->add('authGuard')->add(new NoCache());

$app->group('/modals/quiz/{slug}/meta', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\Hq\Controller\QuizMetaController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Hq\Controller\QuizMetaController:getModalCreate');

    $this->get('/edit', 'UserFrosting\Sprinkle\Hq\Controller\QuizMetaController:getModalEdit');
})->add('authGuard')->add(new NoCache());