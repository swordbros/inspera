<?php namespace Swordbros\Booking\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use Redirect;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Booking\models\BookingRequestModel;
use Swordbros\Event\Models\EventModel;

class BookingRequest extends BaseController
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
    public function onDeclineBookingRequest($recordId = null){
        if($recordId){
            $item = BookingRequestModel::find($recordId);
            if($item->status){
                Flash::warning('Allready approved');
            } else{
                $item->decline();
                Flash::warning('Booking Request Decline');
            }
        }
    }
    public function onApproveBookingRequest($recordId = null){
        if($recordId){
            $item = BookingRequestModel::find($recordId);
            if($item->status){
                Flash::warning('Allready approved');
            } else {
                $booking = $item->approve(
                    \Input::get('BookingRequestModel.booking_status'),
                    \Input::get('BookingRequestModel.payment_method'),
                    \Input::get('BookingRequestModel.payment_method'),
                    floatval(\Input::get('BookingRequestModel.total'))
                );
                if($booking->id){
                    return Redirect::to(Backend::url('swordbros/booking/booking/update', ['id'=>$booking->id]));
                }
                Flash::success('Approved, Saved');
            }
            $this->record = $item;
        } else {
            Flash::error('error');
        }
    }
    public function onToExcel(){

        $widget = $this->asExtension('ListController')->makeList();
        $widget->recordsPerPage= 1000;
        $widget->prepareVars();
        dd($widget->vars['records']->items());
        $model = $this->asExtension('ListController');

        dd(get_class_methods($model));
        // Verileri sorgula (örneğin, tüm kayıtları al)
        $records = $model->newQuery()->get();

        // Filtrelenmiş verileri almak için sorguyu başlat

        dd($filters);

    }
}
