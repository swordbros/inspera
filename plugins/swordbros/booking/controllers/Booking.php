<?php namespace Swordbros\Booking\Controllers;

use Backend;
use Backend\Classes\FormField;
use Backend\Models\User;
use Backend\Widgets\Form;
use BackendMenu;
use Backend\Classes\Controller;
use Input;
use Mail;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Event\Models\EventModel;
use Yaml;

class Booking extends BaseController
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $form = null;
    public function __construct()
    {
        if(Backend\Classes\BackendController::$action=='email'){
            $this->formConfig = 'config_form_email.yaml';
        }
        parent::__construct();
        BackendMenu::setContext('Swordbros.Booking', 'main-menu-item', 'side-menu-item3');
        $eventId = input('event_id');
        if ($eventId) {
            $eventModel = EventModel::find($eventId);
            if($eventModel){
                $this->pageTitle = $eventModel->title;
            }
        }
    }
    public function onSendEmail($bookingId){
        $data['bookingModel'] = Input::get('BookingModel', []);
        if(empty($data['bookingModel'])){
            \Flash::error('Email is empty');
            return;
        }
        if(filter_var( $data['bookingModel']['email'], FILTER_VALIDATE_EMAIL) !== false){
            $booking = BookingModel::find($bookingId);
            $data['email_body'] = $data['bookingModel']['email_body'];
            $data['send_email'] = $data['bookingModel']['email'];
            $data['booking'] = $booking->toArray();
            Mail::sendTo([$data['send_email'] =>$data['bookingModel']['email_title']], 'swordbros.booking_notify', $data);
            Amele::addBookingHistory($data['booking']['id'], 'Email gönderildi: '.$data['bookingModel']['email_title']);
            \Flash::success('Email sended');
        } else{
            \Flash::error('Email is incorrect: '.$data['bookingModel']['email']);
        }

    }
    public function listExtendQuery($query){
        $eventId = input('event_id');
        if ($eventId) {
              $query->where('event_id', $eventId);
        }
    }
    public function email($recordId = null, $context = null)
    {
        $this->asExtension('FormController')->update($recordId, 'update');
    }

}
