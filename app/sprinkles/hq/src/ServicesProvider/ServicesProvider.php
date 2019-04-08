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
            
            $classMapper->setClassMapping('question_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuestionSprunje');
            $classMapper->setClassMapping('question', 'UserFrosting\Sprinkle\Hq\Database\Models\Question');

            $classMapper->setClassMapping('question_options_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuestionOptionsSprunje');
            $classMapper->setClassMapping('question_options', 'UserFrosting\Sprinkle\Hq\Database\Models\QuestionOptions');

            $classMapper->setClassMapping('question_meta_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuestionMetaSprunje');
            $classMapper->setClassMapping('question_meta', 'UserFrosting\Sprinkle\Hq\Database\Models\QuestionMeta');

            $classMapper->setClassMapping('quiz_sprunje', 'UserFrosting\Sprinkle\Hq\Sprunje\QuizSprunje');
            $classMapper->setClassMapping('quiz', 'UserFrosting\Sprinkle\Hq\Database\Models\Quiz');
            
            return $classMapper;
        });

    }
}
