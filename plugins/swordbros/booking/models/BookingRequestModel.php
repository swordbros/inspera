<?php namespace Swordbros\Booking\models;

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
class BookingRequestModel extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_booking_requests';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    /*public $belongsToMany = [
        'bookingrequest_zone' => [ZoneModel::class, 'table' => 'backend_users_groups'],
        'bookingrequest_type' => [BookingRequestModel::class, 'table' => 'backend_users_groups'],
        'bookingrequest_category' => [CategoryModel::class, 'table' => 'backend_users_groups'],
    ];*/
    public $belongsTo = [
        'event' => EventModel::class,
        'user' => User::class,
    ];
    public $fillable = [
        'first_name',
        'last_name',
        'event_id',
        'user_id',
        'email',
        'phone',
        'note',
    ];
    public function getBookingRequestEventIdOptions(){
        $result = [];
        foreach (CategoryModel::all() as $item) {
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
    public function getBookingRequestUserIdOptions(){
        $result = [];
        foreach (TypeModel::all() as $item) {
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
}
