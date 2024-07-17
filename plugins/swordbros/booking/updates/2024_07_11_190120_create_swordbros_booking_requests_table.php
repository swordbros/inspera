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
        Schema::create('swordbros_booking_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_id');
            $table->integer('booking_id')->nullable()->default(null);
            $table->integer('user_id')->nullable()->default(null);
            $table->string('first_name', 255)->nullable()->default(null);
            $table->string('last_name', 255)->nullable()->default(null);
            $table->string('username', 255)->nullable()->default(null);
            $table->string('email', 255)->nullable()->default(null);
            $table->string('phone', 13)->nullable()->default(null);
            $table->string('city', 255)->nullable()->default(null);
            $table->mediumText('note')->nullable();
            $table->string('booking_status', 32)->nullable()->default(null);
            $table->decimal('total', 11, 2)->nullable()->default(null);
            $table->string('payment_method', 32)->nullable()->default(null);
            $table->string('payment_status', 32)->nullable()->default(null);
            $table->integer('status');
            $table->string('otp', 64)->nullable()->default(null);
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
        Schema::dropIfExists('swordbros_booking_requests');
    }
};
