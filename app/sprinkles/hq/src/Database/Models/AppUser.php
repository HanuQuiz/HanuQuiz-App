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
 * HQ App Class
 *
 * Represents a App Object as stored in the database.
 *
 * @author Ayansh TechnoSoft (https://ayansh.com)
 * @see http://www.userfrosting.com/tutorials/lesson-3-data-model/
 *
 * @property string AppName
 * @property string IsActive
 * @property string AppID
 */
class AppUser extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'hq_app_user';

    protected $fillable = [
        'app_id',
        'user_id'
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Delete this App from the database, along with any user associations
     *
     * @todo What do we do with users when their App is deleted?  Reassign them?  Or, can a user be "Appless"?
     */
    public function delete()
    {
        // Delete the group
        $result = parent::delete();

        return $result;
    }

}
