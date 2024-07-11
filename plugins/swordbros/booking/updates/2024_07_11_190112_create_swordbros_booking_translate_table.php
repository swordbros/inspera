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
        Schema::create('swordbros_booking_translate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plugin', 255)->index();
            $table->integer('record_id')->index();
            $table->integer('site_id')->index();
            $table->string('translate_key', 255)->index();
            $table->text('translate_value')->nullable();
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
        Schema::dropIfExists('swordbros_booking_translate');
    }
};
