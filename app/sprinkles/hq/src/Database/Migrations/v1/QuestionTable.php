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
 * HQ Apps table migration
 * Version 1
 *
 * See https://laravel.com/docs/5.4/migrations#tables
 * @author Ayansh TechnoSoft (https://ayansh.com)
 */
class QuestionTable extends Migration
{
    public static $dependencies = [
        '\UserFrosting\Sprinkle\Hq\Database\Migrations\v1\AppsTable'
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('hq_question')) {
            $this->schema->create('hq_question', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('app_id')->unsigned();
                $table->text('question')->nullable();
                $table->tinyInteger('level');
                $table->tinyInteger('choice_type');
                $table->string('status');
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';
                
                $table->index('app_id');

                $table->foreign('app_id')->references('id')->on('hq_apps');
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->drop('hq_question');
    }
}
