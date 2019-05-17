<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('com_lesson_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lesson_id')->index();
            $table->integer('user_id')->index();
            $table->timestamps();
        });

        Schema::create('com_track_enrollments', function (Blueprint $table) {
            $table->integer('track_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('percent_progress')->unsigned()->default(0);
            $table->boolean('is_completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->primary(['track_id', 'user_id']);
            $table->index('user_id');
            $table->index('track_id');
            $table->index('is_completed');
        });

        Schema::create('com_assignment_enrollments', function (Blueprint $table) {
            $table->integer('assignment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('percent_progress')->unsigned()->default(0);
            $table->boolean('is_completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->primary(['assignment_id', 'user_id']);
            $table->index('user_id');
            $table->index('assignment_id');
            $table->index('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('com_lesson_views');
        Schema::drop('com_track_enrollments');
        Schema::drop('com_assignment_enrollments');
    }
}
