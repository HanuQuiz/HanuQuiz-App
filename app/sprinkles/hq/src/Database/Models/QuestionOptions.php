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
 * HQ Question Option Class
 *
 * Represents a Question Option Object as stored in the database.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 * @see http://www.userfrosting.com/tutorials/lesson-3-data-model/
 *
 * @property string question_id
 * @property string option_id
 * @property string option
 * @property string correct_answer
 */
class QuestionOptions extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'hq_q_options';

    protected $fillable = [
        'question_id',
        'option_id',
        'option',
        'correct_answer'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Delete this Question Option from the database
     */
    public function delete()
    {
        // Delete the question
        $result = parent::delete();

        return $result;
    }

    /**
     * Lazily load Question to which this question belongs
     */
    public function question()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('question'), 'question_id');
    }

}
