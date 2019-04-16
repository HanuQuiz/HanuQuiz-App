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
 * HQ Quiz Question Mapping Class
 *
 * Represents a uiz Question Mapping Object as stored in the database.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 * @see http://www.userfrosting.com/tutorials/lesson-3-data-model/
 *
 * @property string question_id
 * @property string quiz_id
 */
class QuizQuestions extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'hq_quiz_questions';

    protected $fillable = [
        'question_id',
        'quiz_id'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Delete this Question Quiz mapping from the database
     */
    public function delete()
    {
        // Delete the question
        $result = parent::delete();

        return $result;
    }

}