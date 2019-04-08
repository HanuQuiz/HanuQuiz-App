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
 * Controller class for Options-related requests, including listing questions, CRUD for Apps, etc.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class QuestionOptionController extends SimpleController
{
	/**
     * Processes the request to create a new option for question (from the admin controls).
     *
     * Processes the request from the application creation form, checking that:
     * 1. <<>>
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
        $schema = new RequestSchema('schema://requests/option/create.yaml');

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

        // PUT Validations here
        
        if ($error) {
            return $response->withJson([], 400);
        }

        /** @var \UserFrosting\Support\Repository\Repository $config */
        $config = $this->ci->config;

        // All checks passed!  log events/activities and create App
        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction(function () use ($classMapper, $data, $ms, $config, $currentUser) {
            // Create the App
            $option = $classMapper->createInstance('question_options', $data);


            // Store new Option to database
            $option->save();

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} created option {$option->id}.", [
                'type'    => 'question_create',
                'user_id' => $currentUser->id
            ]);

            $ms->addMessageTranslated('success', 'QUESTION.OPTION.SUCCESS', $data);
        });

        return $response->withJson([], 200);
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
        $question_id = $this->getQuestionIDFromParams($args);

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

        // Create a dummy option to prepopulate fields
        $option = $classMapper->createInstance('question_options', []);


        //$fieldNames = ['question_id', 'option', 'correct_answer'];
        $fields = [
            'hidden'   => [],
            'disabled' => []
        ];

        // Load validation rules
        $schema = new RequestSchema('schema://requests/option/create.yaml');
        $validator = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'modals/option.html.twig', [
            'option'        => $option,
            'question_id'   => $question_id,
            'form'  => [
                'action'      => 'api/options',
                'method'      => 'POST',
                'fields'      => $fields,
                'submit_text' => $translator->translate('CREATE')
            ],
            'page' => [
                'validators'    => $validator->rules('json', false),
                'question_id'   => $question_id
            ]
        ]);
    }

    /**
     * Renders the modal form for editing an existing option.
     *
     * This does NOT render a complete page.  Instead, it renders the HTML for the modal, which can be embedded in other pages.
     * This page requires authentication.
     *
     * Request type: GET
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws NotFoundException  If option is not found
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function getModalEdit(Request $request, Response $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        $option = $this->getOptionFromParams($params);

        // If the option doesn't exist, return 404
        if (!$option) {
            throw new NotFoundException();
        }

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        /** @var \UserFrosting\I18n\MessageTranslator $translator */
        $translator = $this->ci->translator;

        // Access-controlled resource - check that currentUser has permission to edit basic fields "name", "slug", "icon", "description" for this group
        $fieldNames = ['name', 'slug', 'icon', 'description'];
        
        // Generate form
        $fields = [
            'hidden'   => [],
            'disabled' => []
        ];

        // Load validation rules
        $schema = new RequestSchema('schema://requests/option/create.yaml');
        $validator = new JqueryValidationAdapter($schema, $translator);

        return $this->ci->view->render($response, 'modals/option.html.twig', [
            'option' => $option,
            'question_id'   => $option->question_id,
            'form'  => [
                'action'      => "api/options/opt/{$option->id}",
                'method'      => 'PUT',
                'fields'      => $fields,
                'submit_text' => $translator->translate('UPDATE')
            ],
            'page' => [
                'validators' => $validator->rules('json', false)
            ]
        ]);
    }

    /**
     * Processes the request to update an existing option details.
     *
     * Processes the request from the option update form, checking that:
     * 1. The option id are not already in use;
     * 2. The user has the necessary permissions to update the posted field(s);
     * 3. The submitted data is valid.
     * This route requires authentication (and should generally be limited to admins or the root user).
     *
     * Request type: PUT
     * @see getModalGroupEdit
     * @param  Request            $request
     * @param  Response           $response
     * @param  array              $args
     * @throws NotFoundException  If option is not found
     * @throws ForbiddenException If user is not authozied to access page
     */
    public function updateInfo(Request $request, Response $response, $args)
    {
        // Get the option based on slug in URL
        $option = $this->getOptionFromParams($args);

        if (!$option) {
            throw new NotFoundException();
        }

        /** @var \UserFrosting\Support\Repository\Repository $config */
        $config = $this->ci->config;

        // Get PUT parameters: (name, slug, icon, description)
        $params = $request->getParsedBody();

        /** @var \UserFrosting\Sprinkle\Core\Alert\AlertStream $ms */
        $ms = $this->ci->alerts;

        // Load the request schema
        $schema = new RequestSchema('schema://requests/option/create.yaml');

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

        // Determine targeted fields
        $fieldNames = [];
        foreach ($data as $name => $value) {
            $fieldNames[] = $name;
        }

        /** @var \UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled resource - check that currentUser has permission to edit submitted fields for this option
        // < NOT REQUIRED AS OF NOW >

        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Data Level checks - not required as of now

        if ($error) {
            return $response->withJson([], 400);
        }

        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction(function () use ($data, $option, $currentUser) {
            // Update the option and generate success messages
            foreach ($data as $name => $value) {
                if ($value != $option->$name) {
                    $option->$name = $value;
                }
            }

            $option->save();

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} updated details for option {$option->id}.", [
                'type'    => 'option_update_info',
                'user_id' => $currentUser->id
            ]);
        });

        $ms->addMessageTranslated('success', 'QUESTION.OPTION.EDIT_OK', [
            'name' => $option->option
        ]);

        return $response->withJson([], 200);
    }
    
    /**
     * Get Question ID from params
     *
     * @param  array               $params
     * @throws BadRequestException
     * @return App
     */
    protected function getQuestionIDFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/question/get-by-id.yaml');

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

        return $data['id'];
    }

    /**
     * Get option data from params
     *
     * @param  array               $params
     * @throws BadRequestException
     * @return Group
     */
    protected function getOptionFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/option/get-by-id.yaml');

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

        // Get the option
        $option = $classMapper->staticMethod('question_options', 'where', 'id', $data['id'])
            ->first();

        return $option;
    }

}