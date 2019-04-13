<?php
/**
 * Hanu-Quiz (https://hanu-quiz.varunverma.org)
 *
 * @link      https://projects.ayansh.com/projects/hanu-quiz-framework
 * @copyright Copyright (c) 2019 Ayansh TechnoSoft
 * @license   (MIT License)
 */

namespace UserFrosting\Sprinkle\Hq\Sprunje;

use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;

/**
 * QuestionMetaSprunje
 *
 * Implements Sprunje for the Quiz Meta API.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class QuizMetaSprunje extends Sprunje
{
    protected $name = 'quiz_meta';

    protected $sortable = [
        'meta_key',
        'meta_value'
    ];

    protected $filterable = [
        'meta_key',
        'meta_value'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        return $this->classMapper->createInstance('quiz_meta');
    }
}
