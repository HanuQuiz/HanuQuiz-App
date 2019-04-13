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
class QuestionSprunje extends Sprunje
{
    protected $name = 'questions';

    protected $sortable = [
        'slug',
        'app_id',
        'level',
        'choice_type',
        'status'
    ];

    protected $filterable = [
        'slug',
        'app_id',
        'level',
        'choice_type',
        'status'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        $query = $this->classMapper->createInstance('question')->newQuery();
        $query->join('hq_app_user', 'hq_app_user.app_id','=', 'hq_question.app_id');
        return $query;
    }
}
