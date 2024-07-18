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
        Schema::create('swordbros_booking_payment_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 32)->nullable()->default(null);
            $table->string('name', 255);
            $table->string('description', 255)->nullable()->default(null);
            $table->string('thumb', 255)->nullable()->default(null);
            $table->string('color', 32)->nullable()->default(null);
            $table->string('icon', 255)->nullable()->default(null);
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
        Schema::dropIfExists('swordbros_booking_payment_statuses');
    }
};
