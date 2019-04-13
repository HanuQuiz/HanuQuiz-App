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
 * HQ Quiz Meta Class
 *
 * Represents a Quiz Meta Object as stored in the database.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 * @see http://www.userfrosting.com/tutorials/lesson-3-data-model/
 *
 * @property string quiz_id
 * @property string meta_key
 * @property string meta_value
 */
class QuizMeta extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'hq_quiz_meta';

    protected $fillable = [
        'quiz_id',
        'meta_key',
        'meta_value'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Delete this Meta data from the database
     */
    public function delete()
    {
        // Delete the question
        $result = parent::delete();

        return $result;
    }

    /**
     * Lazily load Question to which this meta data belongs
     */
    public function quiz()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('quiz'), 'quiz_id');
    }

}
