<?php
/**
 * Hanu-Quiz (https://hanu-quiz.varunverma.org)
 *
 * @link      https://projects.ayansh.com/projects/hanu-quiz-framework
 * @copyright Copyright (c) 2019 Ayansh TechnoSoft
 * @license   (MIT License)
 */

namespace UserFrosting\Sprinkle\Hq\Database\Migrations\v1;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;
use UserFrosting\Sprinkle\Core\Facades\Seeder;

/**
 * HQ App Moderators table migration
 * Version 1
 *
 * See https://laravel.com/docs/5.4/migrations#tables
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class AppUserTable extends Migration
{

    public static $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
        '\UserFrosting\Sprinkle\Hq\Database\Migrations\v1\AppsTable'
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('hq_app_user')) {
            $this->schema->create('hq_app_user', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('app_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('app_id')->references('id')->on('hq_apps');

            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->drop('hq_app_user');
    }
}
