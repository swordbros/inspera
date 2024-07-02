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
    public $translateClass = BookingTranslateModel::class;

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
    function getUserIdOptions(){
        $items = ['0'=>'Select One'];
        foreach(User::all() as $item){
            $items[$item->id] = [$item->first_name, $item->liast_name];
        }
        return $items;
    }
    function getEventIdOptions(){
        $items = [];
        foreach(EventModel::all() as $item){
            $items[$item->id] = [$item->title.' ('.$item->start.')'];
        }
        return $items;
    }
    function getBookingStatusOptions(){
        return \Swordbros\Base\Controllers\Amele::getBookingStatusOptions();
    }
    function getPaymentMethodOptions(){
        return \Swordbros\Base\Controllers\Amele::getPaymentMethodOptions();
    }
    function getPaymentStatusOptions(){
        return \Swordbros\Base\Controllers\Amele::getPaymentStatusOptions();
    }
}
