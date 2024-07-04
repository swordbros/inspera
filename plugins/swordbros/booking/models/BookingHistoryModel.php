<?php namespace Swordbros\Booking\Models;

use Backend\Models\User;
use Model;
use Swordbros\BookingRequest\Controllers\Amele;
use Swordbros\BookingRequest\Models\CategoryModel;
use Swordbros\BookingRequest\Models\TypeModel;
use Swordbros\BookingRequest\Models\ZoneModel;
use Swordbros\Event\Models\EventModel;

/**
 * Model
 */
class BookingHistoryModel extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_booking_histories';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
