<?php namespace Swordbros\Booking\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use Redirect;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Booking\models\BookingRequestModel;
use Swordbros\Event\Models\EventModel;

class BookingRequest extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        /**
        $event_type = \Input::get('event_type');
        if($event_type){
            $this->formConfig = 'config_form_'.$event_type.'.yaml';
        }
        */
        parent::__construct();
        BackendMenu::setContext('Swordbros.Booking', 'main-menu-item', 'side-menu-item2');
    }

    public static function fromComponent(){
        $event = new BookingRequest();
        $model = new EventModel();

        $event->initForm($model);
        $event->formRender();
    }
    public static function emptyForm(){
        $event = new BookingRequest();
        $model = new EventModel();
        $event->layout = 'empty';
        $event->asExtension('FormController')->initForm($model);
        return $event->makePartial('blank_form');
    }
    public function onApproveBookingRequest($recordId = null){
        if($recordId){
            $item = BookingRequestModel::find($recordId);
            if($item->status){
                Flash::warning('Allready approved');
            } else {
                $item->status = 1;
                $item->user_id = (int)$item->user_id;
                $item->save();
                Amele::addBookingRequestHistory($recordId, 'Randevu isteği onaylandı');
                $booking = new BookingModel();
                $booking->event_id = $item->event_id;
                $booking->user_id = $item->user_id;
                $booking->first_name = $item->first_name;
                $booking->last_name = $item->last_name;
                $booking->email = $item->email;
                $booking->phone = $item->phone;
                $booking->booking_status = 'pending';
                $booking->save();
                Amele::addBookingRequestHistory($recordId, 'Onaylanan Randevu otomatik oluşturuldu. Randevu Id: '.$booking->id);
                Amele::addBookingHistory($booking->id, 'Onaylanan Randevu otomatik oluşturuldu. Randevu Request Id: '.$recordId);
                Flash::success('Approved, Saved');
                if($booking->id){
                    return Redirect::to(Backend::url('swordbros/booking/booking/update', ['id'=>$booking->id]));
                }

            }
            Flash::success('Approved, Saved');
            $this->record = $item;
        }
    }

}
