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
        Schema::create('swordbros_booking_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_id');
            $table->integer('user_id')->nullable()->default(null);
            $table->string('first_name', 255)->nullable()->default(null);
            $table->string('last_name', 255)->nullable()->default(null);
            $table->string('username', 255)->nullable()->default(null);
            $table->string('email', 255)->nullable()->default(null);
            $table->string('phone', 13)->nullable()->default(null);
            $table->string('city', 255)->nullable()->default(null);
            $table->mediumText('note')->nullable();
            $table->string('booking_status', 16);
            $table->string('payment_method', 16)->nullable()->default(null);
            $table->string('payment_status', 16)->nullable()->default(null);
            $table->string('payment_transaction', 255)->nullable()->default(null);
            $table->decimal('total', 11, 2)->nullable()->default(null);
            $table->string('currency', 4)->nullable()->default(null);
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
        Schema::dropIfExists('swordbros_booking_bookings');
    }
};
