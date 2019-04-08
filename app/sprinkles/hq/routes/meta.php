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
 * Routes for Meta management.
 */

$app->group('/api/meta', function () {

    $this->post('', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:create');

    $this->put('/m/{id}', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:updateInfo');

})->add('authGuard')->add(new NoCache());

$app->group('/modals/q/{id}/meta', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:getModalCreate');

    $this->get('/edit', 'UserFrosting\Sprinkle\Hq\Controller\QuestionMetaController:getModalEdit');
})->add('authGuard')->add(new NoCache());