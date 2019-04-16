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
 * HQ Quiz Class
 *
 * Represents a Quiz Object as stored in the database.
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
        'name',
        'slug',
        'description',
        'level',
        'status'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Delete this Quiz from the database, along with any options
     */
    public function delete()
    {
        // Delete the question
        $result = parent::delete();

        return $result;
    }

    /**
     * Lazily load meta data of this quiz
     */
    public function meta()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->hasMany($classMapper->getClassMapping('quiz_meta'), 'quiz_id');
    }

    /**
     * Lazily load App to which this question belongs
     */
    public function app()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('app'), 'app_id');
    }

    /**
     * Lazily load questions which belong to this question.
     */
    public function questions()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsToMany($classMapper->getClassMapping('question'), 'hq_quiz_questions', 'quiz_id', 'question_id');

    }

    /**
     * Lazily load questions which belong to this question.
     */
    public function question_list()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->hasMany($classMapper->getClassMapping('quiz_questions'), 'quiz_id');

    }
}