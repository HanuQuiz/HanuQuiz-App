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
use UserFrosting\Sprinkle\Account\Database\Models\Role;

/**
 * Seeder for the HQ roles
 */
class HQRoles extends BaseSeed
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $roles = $this->getRoles();

        foreach ($roles as $role) {
            // Don't save if already exist
            if (Role::where('slug', $role->slug)->first() == null) {
                $role->save();
            }
        }
    }

    /**
     * @return array Roles to seed
     */
    protected function getRoles()
    {
        return [
            new Role([
                'slug'        => 'hq-admin',
                'name'        => 'HQ Administrator',
                'description' => 'This role is meant for "HQ administrators", who can basically do anything'
            ]),
            new Role([
                'slug'        => 'hq-app-moderator',
                'name'        => 'App Moderator',
                'description' => 'This role is meant for "App Moderators", who can basically do anything related to that app.'
            ])
        ];
    }
}
