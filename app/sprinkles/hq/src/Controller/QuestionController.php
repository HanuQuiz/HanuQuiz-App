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
 * Controller class for Questions-related requests, including listing questions, CRUD for Apps, etc.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class QuestionController extends SimpleController
{
	/**
     * Processes the request to create a new Question (from the admin controls).
     *
     * Processes the request from the application creation form, checking that:
     * 1. The App Name is not already in use;
     * 2. The logged-in user has the necessary permissions to update the posted field(s);
     * 3. The submitted data is valid.
     * This route requires authentication.
     *
     * Request type: POST
     * @see getModalCreate
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function create(Request $request, Response $response, $args)
    {
        // Get POST parameters: name, slug, icon, description
        $params = $request->getParsedBody();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'create_question')) {
            throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Alert\AlertStream $ms */
        $ms = $this->ci->alerts;

        // Load the request schema
        $schema = new RequestSchema('schema://requests/question/create.yaml');

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

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Check if name or slug already exists
        if ($classMapper->staticMethod('question', 'where', 'question', $data['question'])->first()) {
            $ms->addMessageTranslated('danger', 'QUESTION.IN_USE', $data);
            $error = true;
        }

        if ($error) {
            return $response->withJson([], 400);
        }

        // Final Authorization check
        if (!$authorizer->checkAccess($currentUser, 'uri_own_app', ['app_id' => $data['app_id']])) {
            throw new ForbiddenException();
        }

        /** @var \UserFrosting\Support\Repository\Repository $config */
        $config = $this->ci->config;

        // All checks passed!  log events/activities and create App
        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction(function () use ($classMapper, $data, $ms, $config, $currentUser) {
            // Create the App
            $question = $classMapper->createInstance('question', $data);

            // Make it inactive by default
            $question->status = '';

            // Store new App to database
            $question->save();

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} created question {$question->question}.", [
                'type'    => 'question_create',
                'user_id' => $currentUser->id
            ]);

            $ms->addMessageTranslated('success', 'QUESTION.CREATION_SUCCESSFUL', $data);
        });

        return $response->withJson([], 200);
    }

    /**
     * Renders the Questions List page.
     *
     * This page renders a table of questions, with details and menus for admin actions for each question.
     * Actions typically include: edit details, activate / deactivate, delete question.
     *
     * This page requires authentication.
     * Request type: GET
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function questionList(Request $request, Response $response, $args)
    {
        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_questions')) {
            throw new ForbiddenException();
        }

        return $this->ci->view->render($response, 'pages/question_list.html.twig');
    }

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
    public function getList(Request $request, Response $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_questions')) {
            throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        $sprunje = $classMapper->createInstance('question_sprunje', $classMapper, $params);
        $sprunje->extendQuery(function ($query) {
            $query->with('app');
            $query->where('hq_app_user.user_id', $this->ci->currentUser->id);
            return $query;
        });

        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        return $sprunje->toResponse($response);

    }

    /**
     * Renders the modal form for creating a new Question.
     *
     * This does NOT render a complete page.  Instead, it renders the HTML for the modal, which can be embedded in other pages.
     * This page requires authentication.
     *
     * Request type: GET
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function getModalCreate(Request $request, Response $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        /** @var \UserFrosting\I18n\MessageTranslator $translator */
        $translator = $this->ci->translator;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'create_question')) {
            throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Create a dummy group to prepopulate fields
        $question = $classMapper->createInstance('question', []);

        // App List
        $apps = $classMapper->createInstance('app',[])->appsOfUser($currentUser->id);
        //$apps = $classMapper->createInstance('app',[])->appsOfUser(1);

        //$fieldNames = ['app_id', 'question', 'level', 'choice_type', 'status'];
        $fields = [
            'hidden'   => [],
            'disabled' => []
        ];

        // Load validation rules
        $schema = new RequestSchema('schema://requests/question/create.yaml');
        $validator = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'modals/question.html.twig', [
            'question'      => $question,
            'apps'          => $apps,
            'levels'        => $this->ci->config['levels'],
            'choice_types'  => $this->ci->config['choice_types'],
            'form'  => [
                'action'      => 'api/questions',
                'method'      => 'POST',
                'fields'      => $fields,
                'submit_text' => $translator->translate('CREATE')
            ],
            'page' => [
                'validators' => $validator->rules('json', false)
            ]
        ]);
    }

    /**
     * Renders a page displaying a Question information, in read-only mode.
     *
     * This checks that the currently logged-in user has permission to view the requested question info.
     * It checks each field individually, showing only those that you have permission to view.
     * This will also try to show buttons for deleting, and editing the question.
     * This page requires authentication.
     *
     * Request type: GET
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function pageInfo(Request $request, Response $response, $args)
    {
        $question = $this->getQuestionFromParams($args);

        // If the app no longer exists, forward to main app listing page
        if (!$question) {
            throw new NotFoundException();
        }

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;
        
        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_own_app', ['app_id' => $question->app_id])) {
            throw new ForbiddenException();
        }

        // Determine fields that currentUser is authorized to view
        $fieldNames = ['app_id', 'question', 'level', 'choice_type', 'status'];

        // Generate form
        $fields = [
            'hidden' => []
        ];

        // Determine buttons to display
        $editButtons = [
            'hidden' => []
        ];

        if (!$authorizer->checkAccess($currentUser, 'delete_question', [
            'question' => $question
        ])) {
            $editButtons['hidden'][] = 'delete';
        }

        return $this->ci->view->render($response, 'pages/question.html.twig', [
            'question'  => $question,
            'fields'    => $fields,
            'tools'     => $editButtons,
            'delete_redirect' => $this->ci->router->pathFor('uri_questions')
        ]);
    }

    /**
     * Options List API
     *
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws NotFoundException  If question is not found
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function getOptions(Request $request, Response $response, $args)
    {
        $question = $this->getQuestionFromParams($args);

        // If the question no longer exists, forward to main question listing page
        if (!$question) {
            throw new NotFoundException();
        }

        // GET parameters
        $params = $request->getQueryParams();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_own_app', ['app_id' => $question->app_id])) {
            throw new ForbiddenException();
        }

        if (!$authorizer->checkAccess($currentUser, 'uri_questions')) {
            throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        $sprunje = $classMapper->createInstance('question_options_sprunje', $classMapper, $params);
        $sprunje->extendQuery(function ($query) use ($question) {
            return $query->where('question_id', $question->id);
        });
        
        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        return $sprunje->toResponse($response);
    }

    /**
     * Meta List API
     *
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws NotFoundException  If app is not found
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function getMeta(Request $request, Response $response, $args)
    {
        $question = $this->getQuestionFromParams($args);

        // If the question no longer exists, forward to main question listing page
        if (!$question) {
            throw new NotFoundException();
        }

        // GET parameters
        $params = $request->getQueryParams();

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_own_app', ['app_id' => $question->app_id])) {
            throw new ForbiddenException();
        }
        
        if (!$authorizer->checkAccess($currentUser, 'uri_questions')) {
            throw new ForbiddenException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        $sprunje = $classMapper->createInstance('question_meta_sprunje', $classMapper, $params);
        $sprunje->extendQuery(function ($query) use ($question) {
            return $query->where('question_id', $question->id);
        });
        
        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        return $sprunje->toResponse($response);
    }

    /**
     * Get App from params
     *
     * @param  array               $params
     * @throws BadRequestException
     * @return App
     */
    protected function getQuestionFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/question/get-by-slug.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        // Validate, and throw exception on validation errors.
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            // TODO: encapsulate the communication of error messages from ServerSideValidator to the BadRequestException
            $e = new BadRequestException();
            foreach ($validator->errors() as $idx => $field) {
                foreach ($field as $eidx => $error) {
                    $e->addUserMessage($error);
                }
            }
            throw $e;
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Get the app object
        $question = $classMapper->staticMethod('question', 'where', 'slug', $data['slug'])
            ->first();

        return $question;
    }

}