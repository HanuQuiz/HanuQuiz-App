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
$app->group('/sync/api/', function () {
    
    $this->post('Artifacts', 'UserFrosting\Sprinkle\Hq\Controller\SyncAPIController:fetchArtifacts');

    $this->post('Quiz', 'UserFrosting\Sprinkle\Hq\Controller\SyncAPIController:fetchQuiz');

    $this->post('Question', 'UserFrosting\Sprinkle\Hq\Controller\SyncAPIController:fetchQuestion');

})->add(new NoCache());