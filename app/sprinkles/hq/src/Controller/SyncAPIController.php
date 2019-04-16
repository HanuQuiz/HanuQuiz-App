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

/*
*  Own classes
*/
use UserFrosting\Sprinkle\Hq\Database\Models\AppUser as AppUser;
use UserFrosting\Sprinkle\Hq\Database\Models\Quiz as Quiz;
use UserFrosting\Sprinkle\Hq\Database\Models\Question as Question;
use UserFrosting\Sprinkle\Hq\Database\Models\QuizMeta as QuizMeta;
use UserFrosting\Sprinkle\Hq\Database\Models\QuestionMeta as QuestionMeta;

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
     * Returns the Quiz and Question Artifacts .
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
        // Get POST parameters: name, slug, icon, description
        $params = $request->getParsedBody();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Load the request schema
        $schema = new RequestSchema('schema://sync_api/artifacts.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        $error = false;

        // Validate request data
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            $ms->addValidationErrors($validator);
            $error = true;
        }

        if ($error) {
            return $response->withJson([], 400);
        }

        // Get the App to which user is assigned --> It should be exactly one.
        $apps = AppUser::where('user_id',$currentUser->id)->get();
        if(count($apps) == 1){
            $app = $apps[0];
        }
        else{
            throw new ForbiddenException();
        }

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'read_quiz', ['app_id' => $app->app_id])) {
            throw new ForbiddenException();
        }

        $meta_data = json_decode($data['meta_data'],true);

        // Get Quiz Artifacts
        $quiz_data = $this->__getQuizArtifacts($meta_data,$data['quiz_sync_time'],$app->app_id);

        // Get Question Artifacts
        $question_data = $this->__getQuestionArtifacts($meta_data,$data['question_sync_time'],$app->app_id);

        $result = array(
                    'quizzes' => $quiz_data,
                    'questions' => $question_data
                    );
        
        return $response->withJson($result, 200);
    }

    public function fetchQuiz(Request $request, Response $response, $args)
    {
        // Get POST parameters: name, slug, icon, description
        $params = $request->getParsedBody();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Load the request schema
        $schema = new RequestSchema('schema://sync_api/quiz_ids.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        $error = false;

        // Validate request data
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            $ms->addValidationErrors($validator);
            $error = true;
        }

        if ($error) {
            return $response->withJson([], 400);
        }

        // Get the App to which user is assigned --> It should be exactly one.
        $apps = AppUser::where('user_id',$currentUser->id)->get();
        if(count($apps) == 1){
            $app = $apps[0];
        }
        else{
            throw new ForbiddenException();
        }

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'read_quiz', ['app_id' => $app->app_id])) {
            throw new ForbiddenException();
        }

        $ids = array_map('intval', explode(',', $data['quiz_ids']));
        //var_dump($ids);
        $query = Quiz::with(['meta','question_list'])
                    ->where('app_id',$app->app_id)
                    ->whereIn('id', $ids);

        $result = $query->get();
        
        return $response->withJson($result, 200);
    }

    public function fetchQuestion(Request $request, Response $response, $args)
    {
        // Get POST parameters: name, slug, icon, description
        $params = $request->getParsedBody();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Load the request schema
        $schema = new RequestSchema('schema://sync_api/question_ids.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        $error = false;

        // Validate request data
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            $ms->addValidationErrors($validator);
            $error = true;
        }

        if ($error) {
            return $response->withJson([], 400);
        }

        // Get the App to which user is assigned --> It should be exactly one.
        $apps = AppUser::where('user_id',$currentUser->id)->get();
        if(count($apps) == 1){
            $app = $apps[0];
        }
        else{
            throw new ForbiddenException();
        }

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'read_question', ['app_id' => $app->app_id])) {
            throw new ForbiddenException();
        }

        $ids = array_map('intval', explode(',', $data['question_ids']));
        //var_dump($ids);
        $query = Question::with(['meta','options'])
                    ->where('app_id',$app->app_id)
                    ->whereIn('id', $ids);

        $result = $query->get();
        
        return $response->withJson($result, 200);
    }

    private function __getQuizArtifacts($meta_data,$sync_time,$app_id){

        $query = Quiz::select('id','created_at')->whereHas('meta', function($query) use($meta_data){
            foreach ($meta_data as $key => $value) {
                $query->where($key,$value);
            }
        });

        $query = $query->where('updated_at','>',$sync_time)
                    ->where('app_id',$app_id);

        return $query->get();

    }

    private function __getQuestionArtifacts($meta_data,$sync_time,$app_id){

        $query = Question::select('id','created_at')->whereHas('meta', function($query) use($meta_data){
            foreach ($meta_data as $key => $value) {
                $query->where($key,$value);
            }
        });

        $query = $query->where('updated_at','>',$sync_time)
                    ->where('app_id',$app_id);

        return $query->get();

    }

}