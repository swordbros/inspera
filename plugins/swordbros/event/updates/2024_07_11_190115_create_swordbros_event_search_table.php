<?php

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('swordbros_event_search', function (Blueprint $table) {
            $table->string('audience', 16)->nullable();
            $table->text('booking_url')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('color', 32)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('currency', 4)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->mediumText('description')->nullable();
            $table->dateTime('end')->nullable();
            $table->integer('event_category_id')->nullable();
            $table->integer('event_type_id')->nullable();
            $table->integer('event_zone_id')->nullable();
            $table->string('group_key', 32)->nullable();
            $table->unsignedInteger('id')->nullable();
            $table->text('images')->nullable();
            $table->decimal('price', 11, 2)->nullable();
            $table->text('short')->nullable();
            $table->dateTime('start')->nullable();
            $table->integer('status')->nullable();
            $table->text('text')->nullable();
            $table->string('thumb', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swordbros_event_search');
    }
};
