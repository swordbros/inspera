<?php namespace Swordbros\Booking\Models;


use Mail;
use Model;
use RainLab\User\Models\User;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventCategoryModel;
use Swordbros\Event\Models\EventTypeModel;
use Swordbros\Event\Models\EventZoneModel;
use Swordbros\Event\Models\EventModel;
use Swordbros\Setting\Models\SwordbrosSettingModel;

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
    public $translateClass = BookingTranslateModel::class;

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
            Amele::addBookingHistory($row->id, 'Rezervasyon Güncellendi');
            BookingModel::sendEventStatusNotification($row);
        });
        static::created(function ($row) {
            Amele::save_localize_row($row);
        });
    }
    public static function sendEventStatusNotification($row){
        if( $row->changes && isset($row->changes['booking_status'])){
            $new_booking_status = $row->changes['booking_status'];
            $booking_email_template = SwordbrosSettingModel::swordbros_setting('booking_email_'.$new_booking_status);
            if($booking_email_template && $booking_email_template->code){
                $locale = session('locale', 'en'); // Varsayılan olarak 'en'

                $data = $row->toArray();
                $event = EventModel::find($data['event_id']);
                if($event){
                    $data['event'] = $event->toArray();
                    $data['event']['start'] = date('d.m.Y H:i', strtotime($data['event']['start']));
                    $zone = EventZoneModel::find($data['event']['event_zone_id']);
                    if ($zone) {
                        $data['zone'] = $zone->toArray();
                    }
                }
                $data['subject'] = '';
                $data['content'] = '';
                $data['contact_url'] =  url('/'.$locale.'/contact');
                $data['telephone'] =  SwordbrosSettingModel::swordbros_setting('telephone');
                $data['send_email'] = $data['email'];
                Mail::send($booking_email_template->code, $data, function ($message) use ($data) {
                    $message->to($data['send_email'], $data['first_name']);
                });
            }
        }
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
