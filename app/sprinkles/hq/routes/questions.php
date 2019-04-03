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
 * Routes for Questions management.
 */
$app->group('/questions', function () {
    $this->get('', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:questionList')
        ->setName('uri_questions');

    $this->get('/q/{id}', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:pageInfo');
})->add('authGuard')->add(new NoCache());

$app->group('/api/questions', function () {
    $this->delete('/q/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:delete');

    $this->get('', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:getList');

    $this->get('/q/id/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:getInfo');

    $this->post('', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:create');

    $this->put('/q/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:updateInfo');
})->add('authGuard')->add(new NoCache());

$app->group('/modals/question', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:getModalCreate');

    $this->get('/edit', 'UserFrosting\Sprinkle\Hq\Controller\QuestionController:getModalEdit');
})->add('authGuard')->add(new NoCache());
