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
class AppSprunje extends Sprunje
{
    protected $name = 'apps';

    protected $sortable = [
        'slug',
        'name',
        'status'
    ];

    protected $filterable = [
        'slug',
        'name',
        'status'
    ];

    /**
     * {@inheritdoc}
     */
    protected function baseQuery()
    {
        return $this->classMapper->createInstance('app')->newQuery();
    }
}
