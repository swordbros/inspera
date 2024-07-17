<?php namespace Swordbros\Booking\models;


use Model;
use RainLab\User\Models\User;
use Swordbros\Base\Controllers\Amele;
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
    function decline(){
        if($this->booking_id){
            Amele::addBookingRequestHistory($this->id, 'Onaylanan Rezervasyon isteği reddedilemez');
        }
        if($this->status==1){
            $this->status = 0;
            $this->save();
            Amele::addBookingRequestHistory($this->id, 'Rezervasyon isteği reddedildi');
        }

    }
    function approve($booking_status, $payment_method, $payment_status, $total){
        if($this->booking_id){
            $booking = BookingModel::find($this->booking_id);
            Amele::addBookingRequestHistory($this->id, 'Rezervasyon isteği zaten onaylanmış');
            return false;
        }
        $this->status = 1;
        $this->user_id = (int)$this->user_id;
        if($total){
            $this->total = $total;
        }
        if($booking_status){
            $this->booking_status = $booking_status;
        } else {
            $this->booking_status = 'approved';
        }
        if($payment_method){
            $this->payment_method = $payment_method;
        }
        if($payment_status){
            $this->payment_status = $payment_status;
        }
        $booking = new BookingModel();
        $booking->event_id = $this->event_id;
        $booking->user_id = $this->user_id;
        $booking->first_name = $this->first_name;
        $booking->last_name = $this->last_name;
        $booking->email = $this->email;
        $booking->phone = $this->phone;
        $booking->booking_status = $this->booking_status;
        $booking->total = $this->total;
        $booking->payment_method = $this->payment_method;
        $booking->payment_status = $this->payment_status;
        $booking->save();
        $this->booking_id = $booking->id;
        $this->save();
        Amele::addBookingRequestHistory($this->id, 'Rezervasyon isteği onaylandı');
        Amele::addBookingRequestHistory($this->id, 'Onaylanan isteğe göre otomatik rezervasyon oluşturuldu. Rezervasyon Id: '.$booking->id);
        Amele::addBookingHistory($booking->id, 'Onaylanan isteğe göre otomatik rezervasyon oluşturuldu. Rezervasyon Request Id: '.$this->id);
        return $booking;
    }
}
