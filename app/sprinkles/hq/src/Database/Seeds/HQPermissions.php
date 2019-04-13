<?php
/**
 * Hanu-Quiz (https://hanu-quiz.varunverma.org)
 *
 * @link      https://projects.ayansh.com/projects/hanu-quiz-framework
 * @copyright Copyright (c) 2019 Ayansh TechnoSoft
 * @license   (MIT License)
 */

namespace UserFrosting\Sprinkle\Hq\Database\Seeds;

use UserFrosting\Sprinkle\Core\Database\Seeder\BaseSeed;
use UserFrosting\Sprinkle\Account\Database\Models\Permission;
use UserFrosting\Sprinkle\Account\Database\Models\Role;
use UserFrosting\Sprinkle\Core\Facades\Seeder;

/**
 * Seeder for the HQ permissions
 */
class HQPermissions extends BaseSeed
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Get and save permissions
        $permissions = $this->getPermissions();
        $this->savePermissions($permissions);

        // Add default mappings to permissions
        $this->syncPermissionsRole($permissions);
    }

    /**
     * @return array Permissions to seed
     */
    protected function getPermissions()
    {
        $defaultRoleIds = [
            'hq-admin' => Role::where('slug', 'hq-admin')->first()->id,
            'hq-app-moderator'  => Role::where('slug', 'hq-app-moderator')->first()->id
        ];

        return [
            'create_app' => new Permission([
                'slug'        => 'create_app',
                'name'        => 'Create App',
                'conditions'  => 'always()',
                'description' => 'Create a new App.'
            ]),
            'add_moderator' => new Permission([
                'slug'        => 'add_moderator',
                'name'        => 'Add Moderator',
                'conditions'  => 'always()',
                'description' => 'Assign moderator to App.'
            ]),
            'delete_app' => new Permission([
                'slug'        => 'delete_app',
                'name'        => 'Delete App',
                'conditions'  => 'always()',
                'description' => 'Delete a App.'
            ]),
            'uri_apps' => new Permission([
                'slug'        => 'uri_apps',
                'name'        => 'View Apps',
                'conditions'  => 'always()',
                'description' => 'View the Apps of any user.'
            ]),
            'uri_own_app' => new Permission([
                'slug'        => 'uri_own_app',
                'name'        => 'View Own App',
                'conditions'  => 'is_app_user(self.id,app_id)',
                'description' => 'View own apps only'
            ]),
            'create_quiz' => new Permission([
                'slug'        => 'create_quiz',
                'name'        => 'Create Quiz',
                'conditions'  => 'always()',
                'description' => 'Create a new Quiz in your own App'
            ]),
            'delete_quiz' => new Permission([
                'slug'        => 'delete_quiz',
                'name'        => 'Delete Quiz',
                'conditions'  => 'own_quiz(self.id,quiz_id)',
                'description' => 'Delete quiz in own app'
            ]),
            'create_question' => new Permission([
                'slug'        => 'create_question',
                'name'        => 'Create Question',
                'conditions'  => 'always()',
                'description' => 'Create a new Question in your own App'
            ]),
            'delete_question' => new Permission([
                'slug'        => 'delete_question',
                'name'        => 'Delete Question',
                'conditions'  => 'own_question(self.id,question_id)',
                'description' => 'Delete questions in own app.'
            ]),
            'uri_quiz' => new Permission([
                'slug'        => 'uri_quiz',
                'name'        => 'View Quizzes',
                'conditions'  => 'always()',
                'description' => 'View the Quiz List'
            ]),
            'uri_questions' => new Permission([
                'slug'        => 'uri_questions',
                'name'        => 'View Question List',
                'conditions'  => 'always()',
                'description' => 'View the Question List'
            ]),
            'read_app' => new Permission([
                'slug'        => 'read_app',
                'name'        => 'Read Own App',
                'conditions'  => 'is_app_user(self.id,app_id)',
                'description' => 'Read own apps only'
            ]),
            'read_quiz' => new Permission([
                'slug'        => 'read_quiz',
                'name'        => 'Read Own Quiz',
                'conditions'  => 'is_app_user(self.id,app_id)',
                'description' => 'Read Quiz data of own apps only'
            ]),
            'read_question' => new Permission([
                'slug'        => 'read_question',
                'name'        => 'View Own App',
                'conditions'  => 'is_app_user(self.id,app_id)',
                'description' => 'Read Question data of own only'
            ])
        ];
    }

    /**
     * Save permissions
     * @param array $permissions
     */
    protected function savePermissions(array $permissions)
    {
        foreach ($permissions as $slug => $permission) {

            // Trying to find if the permission already exist
            $existingPermission = Permission::where(['slug' => $permission->slug, 'conditions' => $permission->conditions])->first();

            // Don't save if already exist, use existing permission reference
            // otherwise to re-sync permissions and roles
            if ($existingPermission == null) {
                $permission->save();
            } else {
                $permissions[$slug] = $existingPermission;
            }
        }
    }

    /**
     * Sync permissions with default roles
     * @param array $permissions
     */
    protected function syncPermissionsRole(array $permissions)
    {
        $roleAdmin = Role::where('slug', 'hq-admin')->first();
        if ($roleAdmin) {
            $roleAdmin->permissions()->sync([
                $permissions['create_app']->id,
                $permissions['add_moderator']->id,
                $permissions['uri_apps']->id,
                $permissions['delete_app']->id
            ]);
        }
        $roleAppModerator = Role::where('slug', 'hq-app-moderator')->first();
        if ($roleAppModerator) {
            $roleAppModerator->permissions()->sync([
                $permissions['uri_apps']->id,
                $permissions['uri_own_app']->id,
                $permissions['create_quiz']->id,
                $permissions['delete_quiz']->id,
                $permissions['create_question']->id,
                $permissions['delete_question']->id,
                $permissions['uri_quiz']->id,
                $permissions['uri_questions']->id
            ]);
        }
        $roleAppReader = Role::where('slug', 'hq-app-reader')->first();
        if ($roleAppReader) {
            $roleAppReader->permissions()->sync([
                $permissions['read_app']->id,
                $permissions['read_question']->id,
                $permissions['read_quiz']->id
            ]);
        }   
    }
}