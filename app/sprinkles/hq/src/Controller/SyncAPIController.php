<?php
/**
 * Hanu-Quiz (https://hanu-quiz.varunverma.org)
 *
 * @link      https://projects.ayansh.com/projects/hanu-quiz-framework
 * @copyright Copyright (c) 2019 Ayansh TechnoSoft
 * @license   (MIT License)
 */

namespace UserFrosting\Sprinkle\Hq\Controller;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Support\Exception\BadRequestException;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Support\Exception\NotFoundException;

/**
 * Controller class for Sync API Methods
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class SyncAPIController extends SimpleController
{
    /**
     * Returns a list of Apps (API)
     *
     * Generates a list of groups, optionally paginated, sorted and/or filtered.
     * This page requires authentication.
     *
     * Request type: GET
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function fetchArtifacts(Request $request, Response $response, $args)
    {
        $result = array();
        return json_encode($result);
    }

    public function fetchQuiz(Request $request, Response $response, $args)
    {
        $result = array();
        return json_encode($result);
    }

    public function fetchQuestion(Request $request, Response $response, $args)
    {
        $result = array();
        return json_encode($result);
    }

}