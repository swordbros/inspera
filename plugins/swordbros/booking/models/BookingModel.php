<?php namespace Swordbros\Booking\Models;

use Backend\Models\User;
use Model;
use Swordbros\Base\Controllers\Amele;
use Swordbros\BookingRequest\Models\CategoryModel;
use Swordbros\BookingRequest\Models\TypeModel;
use Swordbros\BookingRequest\Models\ZoneModel;
use Swordbros\Event\Models\EventModel;

/**
 * Model
 */
class BookingModel extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_booking_bookings';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'user_id' => 'nullable|integer',
    ];

    public $belongsTo = [
        'event' => EventModel::class,
        'user' => User::class,
    ];
    protected $casts = [
        'user_id' => 'string',
    ];
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($row) {
            Amele::addBookingHistory($row->id, 'Randevu Güncellendi');
        });
        static::created(function ($row) {
            Amele::save_localize_row($row);
        });
    }
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
    function getEventIdOptions(){
        $items = [];
        foreach(EventModel::all() as $item){
            $items[$item->id] = [$item->title.' ('.$item->start.')'];
        }
        return $items;
    }
    function getUserIdOptions(){
        $items = ['0'=>'Select One'];
        foreach(User::all() as $item){
            $items[$item->id] = [$item->first_name, $item->liast_name];
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
    public function scopeIsBookingPaymentMethods($query, $value)
    {
        if ($value) {
            $query->where('payment_method', $value);
        }
    }
    public function scopeIsBookingPaymentStatuses($query, $value)
    {
        if ($value) {
            $query->where('payment_status', $value);
        }
    }
    public function scopeisBookingStatus($query, $value)
    {
        if ($value) {
            $query->where('payment_status', $value);
        }
    }
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public static function getTotalEventBooking($id){
        $bookingModel = new BookingModel();
        return $bookingModel->where(['event_id'=>$id])->count();
    }
    public static function getCapacityEventBooking($id){
        $eventModel = EventModel::select('capacity')->find($id);
        if($eventModel){
            return  $eventModel->capacity;
        }
        return 0;
    }
}
