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
 * AppSprunje
 *
 * Implements Sprunje for the Apps API.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class QuizSprunje extends Sprunje
{
    protected $name = 'quiz';

    protected $sortable = [
        'id',
        'app_id',
        'slug',
        'count',
        'level',
        'status'
    ];

    protected $filterable = [
        'id',
        'app_id',
        'slug',
        'count',
        'level',
        'status'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        return $this->classMapper->createInstance('quiz')->newQuery();
    }
}
