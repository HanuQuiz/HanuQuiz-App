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
        'name',
        'slug',
        'app_id',
        'level',
        'status'
    ];

    protected $filterable = [
        'id',
        'name',
        'slug',
        'app_id',
        'level',
        'status'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        $query = $this->classMapper->createInstance('quiz')->newQuery();
        $query->join('hq_app_user', 'hq_app_user.app_id','=', 'hq_quiz.app_id');
        return $query;
    }
}
