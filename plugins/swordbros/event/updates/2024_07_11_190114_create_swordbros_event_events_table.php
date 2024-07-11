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
        Schema::create('swordbros_event_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group_key', 32)->nullable()->default(null);
            $table->integer('event_zone_id');
            $table->integer('event_category_id');
            $table->integer('event_type_id');
            $table->string('title', 255);
            $table->dateTime('start');
            $table->dateTime('end')->nullable()->default(null);
            $table->string('audience', 16)->nullable()->default(null);
            $table->text('booking_url')->nullable();
            $table->string('thumb', 255)->nullable()->default(null);
            $table->text('images')->nullable();
            $table->text('short')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('color', 32)->nullable()->default(null);
            $table->decimal('price', 11, 2)->nullable()->default(null);
            $table->string('currency', 4)->nullable()->default(null);
            $table->integer('capacity');
            $table->decimal('rating', 11, 2)->nullable()->default(null);
            $table->integer('status');
            $table->timestamp('deleted_at')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swordbros_event_events');
    }
};
