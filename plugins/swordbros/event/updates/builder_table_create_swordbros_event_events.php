<?php namespace Swordbros\Event\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSwordbrosEventEvents extends Migration
{
    public function up()
    {
        Schema::create('swordbros_event_events', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('group_key', 32)->nullable();
            $table->integer('event_zone_id');
            $table->integer('event_category_id');
            $table->integer('event_type_id');
            $table->integer('status');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('swordbros_event_events');
    }
}
