<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCMSObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_objects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('attr1str')->nullable();
            $table->string('attr2str')->nullable();
            $table->text('attr1text')->nullable();
            $table->integer('attr1int')->unsigned()->nullable();
            $table->integer('attr2int')->unsigned()->nullable();
            $table->integer('attr3int')->unsigned()->nullable();
            $table->integer('attr4int')->unsigned()->nullable();
            $table->boolean('attr1bool')->default(0);
            $table->smallInteger('attr1sint')->unsigned()->default(0);
            $table->smallInteger('attr2sint')->unsigned()->nullable();
            $table->double('attr1double', 15, 8)->nullable();
            $table->float('attr1float', 8, 2)->nullable();
            $table->timestamp('attr1ts')->nullable();
            $table->timestamp('attr2ts')->nullable();
            $table->smallInteger('revision')->unsigned()->default(1);
            $table->timestamp('revision_at')->nullable();
            $table->boolean('is_dirty')->default(0);
            $table->integer('created_by')->unsigned()->default(2);
            $table->integer('updated_by')->unsigned()->default(2);
            $table->timestamps();
            $table->index('id');
            $table->dropPrimary('cms_objects_id_primary');
            $table->primary(['attr1sint', 'id']);
            $table->index('attr1sint');
            $table->index('is_dirty');
            $table->index(['attr1sint', 'name', 'attr1bool']);
            $table->index(['attr1sint', 'attr1bool']);
            $table->index(['attr1sint', 'created_at']);
            $table->index(['attr1sint', 'updated_at']);
            $table->index(['attr1sint', 'created_by']);
            $table->index(['attr1sint', 'updated_by']);
            $table->index(['attr1sint', 'attr1int', 'attr2int']);
            $table->index(['attr1sint', 'attr1str', 'attr2str']);
            $table->index(['attr1sint', 'attr1ts', 'attr2ts']);
            $table->index(['attr1sint', 'attr1int']);
            $table->index(['attr1sint', 'attr2int']);
            $table->index(['attr1sint', 'attr3int']);
            $table->index(['attr1sint', 'attr4int']);
        });

        DB::statement("ALTER TABLE cms_objects ADD FULLTEXT INDEX cms_objects_fulltext_index(name, description, attr1str, attr2str, attr1text)");
        DB::statement("CREATE TABLE lib_objects LIKE cms_objects");

        /*DB::statement("ALTER TABLE cms_objects ADD COLUMN created_by INT(10) UNSIGNED DEFAULT 2");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN updated_by INT(10) UNSIGNED DEFAULT 2");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_created_by(attr1sint, created_by)");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_updated_by(attr1sint, updated_by)");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_updated_at(attr1sint, updated_at)");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN revision SMALLINT(5) UNSIGNED DEFAULT 1");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN revision_at TIMESTAMP NULL DEFAULT NULL");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_revision(attr1sint, revision)");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_revision_at(attr1sint, revision_at)");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN is_dirty BOOL DEFAULT 0");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_is_dirty(is_dirty)");
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cms_objects');
    }
}
