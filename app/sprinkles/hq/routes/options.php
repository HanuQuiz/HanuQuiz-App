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
 * Routes for Options management.
 */

$app->group('/api/options', function () {

    $this->post('', 'UserFrosting\Sprinkle\Hq\Controller\QuestionOptionController:create');

    $this->put('/opt/{id}', 'UserFrosting\Sprinkle\Hq\Controller\QuestionOptionController:updateInfo');

})->add('authGuard')->add(new NoCache());

$app->group('/modals/q/{id}/options', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\Hq\Controller\QuestionOptionController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Hq\Controller\QuestionOptionController:getModalCreate');

    $this->get('/edit', 'UserFrosting\Sprinkle\Hq\Controller\QuestionOptionController:getModalEdit');
})->add('authGuard')->add(new NoCache());