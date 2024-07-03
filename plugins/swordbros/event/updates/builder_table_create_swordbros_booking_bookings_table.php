<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwordbrosBookingBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('swordbros_booking_bookings', function (Blueprint $table) {

		$table->integer('id',)->unsigned();
		$table->integer('event_id',);
		$table->integer('user_id',);
		$table->string('first_name')->nullable()->default('NULL');
		$table->string('last_name')->nullable()->default('NULL');
		$table->string('username')->nullable()->default('NULL');
		$table->string('email')->nullable()->default('NULL');
		$table->string('phone',13)->nullable()->default('NULL');
		$table->string('city')->nullable()->default('NULL');
		;
		$table->string('booking_status',16);
		$table->string('payment_method',16)->nullable()->default('NULL');
		$table->string('payment_status',16)->nullable()->default('NULL');
		$table->string('payment_transaction')->nullable()->default('NULL');
		$table->decimal('total',11,2)->nullable()->default('NULL');
		$table->string('currency',4)->nullable()->default('NULL');
		$table->timestamp('deleted_at')->nullable()->default('NULL');
		$table->timestamp('created_at')->nullable()->default('NULL');
		$table->timestamp('updated_at')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('swordbros_booking_bookings');
    }
}