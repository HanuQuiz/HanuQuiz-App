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
class QuestionMetaTable extends Migration
{
    public static $dependencies = [
        '\UserFrosting\Sprinkle\Hq\Database\Migrations\v1\QuestionTable'
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('hq_q_meta')) {
            $this->schema->create('hq_q_meta', function (Blueprint $table) {
                
                $table->increments('id');
                $table->integer('question_id')->unsigned();
                $table->string('meta_key');
                $table->string('meta_value');
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';

                $table->foreign('question_id')->references('id')->on('hq_question');
                
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->drop('hq_q_meta');
    }
}
