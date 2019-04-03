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
 * Routes for Apps management.
 */
$app->group('/apps', function () {
    $this->get('', 'UserFrosting\Sprinkle\Hq\Controller\AppController:appList')
        ->setName('uri_apps');

    $this->get('/app/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\AppController:pageInfo');
})->add('authGuard')->add(new NoCache());

$app->group('/api/apps', function () {
    $this->delete('/app/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\AppController:delete');

    $this->get('', 'UserFrosting\Sprinkle\Hq\Controller\AppController:getList');

    $this->get('/app/id/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\AppController:getInfo');

    $this->get('/app/{slug}/moderators', 'UserFrosting\Sprinkle\Hq\Controller\AppController:getModerators');

    $this->post('', 'UserFrosting\Sprinkle\Hq\Controller\AppController:create');

    $this->put('/app/{slug}', 'UserFrosting\Sprinkle\Hq\Controller\AppController:updateInfo');
})->add('authGuard')->add(new NoCache());

$app->group('/modals/apps', function () {
    $this->get('/confirm-delete', 'UserFrosting\Sprinkle\Hq\Controller\AppController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Hq\Controller\AppController:getModalCreate');

    $this->get('/edit', 'UserFrosting\Sprinkle\Hq\Controller\AppController:getModalEdit');
})->add('authGuard')->add(new NoCache());
