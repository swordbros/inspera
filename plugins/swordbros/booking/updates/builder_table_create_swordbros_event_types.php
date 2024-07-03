<?php namespace Swordbros\Event\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSwordbrosEventTypes extends Migration
{
    public function up()
    {
        Schema::create('swordbros_event_types', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('code', 32);
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('status');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('swordbros_event_types');
    }
}
