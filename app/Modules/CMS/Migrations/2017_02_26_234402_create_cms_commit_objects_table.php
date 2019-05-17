<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCMSCommitObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_commit_objects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('commit_id')->unsigned()->nullable();
            $table->integer('object_id')->unsigned()->nullable();
            $table->timestamps();
            $table->unique(['commit_id', 'object_id']);
            $table->index('commit_id');
            $table->index('object_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cms_commit_objects');
    }
}
