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
 * QuestionOptionsSprunje
 *
 * Implements Sprunje for the Question Options API.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class QuestionOptionsSprunje extends Sprunje
{
    protected $name = 'question_options';

    protected $sortable = [
        'id',
        'otpion',
        'correct_answer'
    ];

    protected $filterable = [
        'id',
        'otpion',
        'correct_answer'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        return $this->classMapper->createInstance('question_options');
    }
}
