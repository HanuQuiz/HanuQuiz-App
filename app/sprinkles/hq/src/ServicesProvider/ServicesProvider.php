<?php
/**
 * Hanu-Quiz (https://hanu-quiz.varunverma.org)
 *
 * @link      https://projects.ayansh.com/projects/hanu-quiz-framework
 * @copyright Copyright (c) 2019 Ayansh TechnoSoft
 * @license   (MIT License)
 */

namespace UserFrosting\Sprinkle\Hq\ServicesProvider;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use UserFrosting\Sprinkle\Hq\Database\Models\AppUser as AppUser;

/**
 * Registers services for the admin sprinkle.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class ServicesProvider
{
    /**
     * Register HQ services.
     *
     * @param ContainerInterface $container A DI container implementing ArrayAccess and container-interop.
     */
    public function register(ContainerInterface $container)
    {
        /**
         * Extend the 'classMapper' service to register sprunje classes.
         *
         * Mappings added: 'app_sprunje'
         *
         * @return \UserFrosting\Sprinkle\Core\Util\ClassMapper
         */
        $container->extend('classMapper', function ($classMapper, $c) {
            
            $classMapper->setClassMapping('app_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\AppSprunje');
            $classMapper->setClassMapping('app', 'UserFrosting\Sprinkle\Hq\Database\Models\App');

            $classMapper->setClassMapping('app_user', 'UserFrosting\Sprinkle\Hq\Database\Models\AppUser');
            
            $classMapper->setClassMapping('question_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuestionSprunje');
            $classMapper->setClassMapping('question', 'UserFrosting\Sprinkle\Hq\Database\Models\Question');

            $classMapper->setClassMapping('question_options_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuestionOptionsSprunje');
            $classMapper->setClassMapping('question_options', 'UserFrosting\Sprinkle\Hq\Database\Models\QuestionOptions');

            $classMapper->setClassMapping('question_meta_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuestionMetaSprunje');
            $classMapper->setClassMapping('question_meta', 'UserFrosting\Sprinkle\Hq\Database\Models\QuestionMeta');

            $classMapper->setClassMapping('quiz_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuizSprunje');
            $classMapper->setClassMapping('quiz', 'UserFrosting\Sprinkle\Hq\Database\Models\Quiz');

            $classMapper->setClassMapping('quiz_meta_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuizMetaSprunje');
            $classMapper->setClassMapping('quiz_meta', 'UserFrosting\Sprinkle\Hq\Database\Models\QuizMeta');
            
            return $classMapper;
        });


        /**
         * Extend the 'classMapper' service to add own authorizer
         * @return \UserFrosting\Sprinkle\Core\Util\authorizer
         */
        $container->extend('authorizer', function ($authorizer, $c) {
            $authorizer->addCallback('is_app_user',
                /**
                 * Check if the specified user (by id) is moderator of the app.
                 *
                 * @param int $user_id the id of the user.
                 * @param int $app_id the id of the organization.
                 * @return bool true if the user is in the moderator list, false otherwise.
                 */
                function ($user_id, $app_id) use ($c) {
                    if(AppUser::where('user_id',$user_id)->where('app_id',$app_id)->first()){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            );

            $authorizer->addCallback('own_quiz',
                /**
                 * Check if the specified user (by id) is moderator of the quiz.
                 *
                 * @param int $user_id the id of the user.
                 * @param int $quiz_id the id of the organization.
                 * @return bool true if the user is in the moderator list, false otherwise.
                 */
                function ($user_id, $quiz_id) use ($c) {
                    return false;
                }
            );

            $authorizer->addCallback('own_question',
                /**
                 * Check if the specified user (by id) is moderator of the question.
                 *
                 * @param int $user_id the id of the user.
                 * @param int $question_id the id of the organization.
                 * @return bool true if the user is in the moderator list, false otherwise.
                 */
                function ($user_id, $question_id) use ($c) {
                    return false;
                }
            );

            return $authorizer;
        });


    }
}
