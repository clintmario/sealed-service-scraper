<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCMSRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_relations', function (Blueprint $table) {
            $table->integer('attr1int')->unsigned()->nullable();
            $table->integer('attr2int')->unsigned()->nullable();
            $table->string('attr1str')->nullable();
            $table->boolean('attr1bool')->default(0);
            $table->smallInteger('attr1sint')->unsigned();
            $table->smallInteger('attr2sint')->unsigned()->nullable();
            $table->double('attr1double', 15, 8)->nullable();
            $table->timestamp('attr1ts')->nullable();
            $table->boolean('is_dirty')->default(0);
            $table->timestamps();
            $table->primary(['attr1sint', 'attr1int', 'attr2int']);
            $table->index('is_dirty');
            $table->index('attr1sint');
            $table->index('attr1int');
            $table->index('attr2int');
            $table->index(['attr1sint', 'attr2int', 'attr1int']);
            $table->index(['attr1sint', 'attr1int', 'attr2sint', 'attr1bool']);
            $table->index(['attr1sint', 'attr1bool']);
            $table->index(['attr1sint', 'created_at']);
        });

        DB::statement("CREATE TABLE lib_relations LIKE cms_relations");

        /*DB::statement("ALTER TABLE cms_relations ADD INDEX IX_from_id(attr1int)");
        DB::statement("ALTER TABLE cms_relations ADD INDEX IX_to_id(attr2int)");
        DB::statement("ALTER TABLE cms_relations ADD COLUMN is_dirty BOOL DEFAULT 0");
        DB::statement("ALTER TABLE cms_relations ADD INDEX IX_is_dirty(is_dirty)");
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cms_relations');
    }
}
