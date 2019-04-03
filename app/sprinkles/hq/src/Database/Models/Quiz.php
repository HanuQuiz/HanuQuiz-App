<?php
/**
 * Hanu-Quiz (https://hanu-quiz.varunverma.org)
 *
 * @link      https://projects.ayansh.com/projects/hanu-quiz-framework
 * @copyright Copyright (c) 2019 Ayansh TechnoSoft
 * @license   (MIT License)
 */

namespace UserFrosting\Sprinkle\Hq\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

/**
 * HQ Question Class
 *
 * Represents a Question Object as stored in the database.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 * @see http://www.userfrosting.com/tutorials/lesson-3-data-model/
 *
 * @property string app_name
 * @property string is_active
 * @property string app_id
 */
class Quiz extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'hq_quiz';

    protected $fillable = [
        'app_id',
        'slug',
        'description',
        'count',
        'level',
        'question_ids',
        'status'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Delete this Question from the database, along with any options
     */
    public function delete()
    {
        // Delete the question
        $result = parent::delete();

        return $result;
    }

    /**
     * Lazily load options which belong to this question.
     */
    public function options()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->hasMany($classMapper->getClassMapping('options'), 'id');
    }
}
