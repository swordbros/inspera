<?php namespace Swordbros\Booking\Controllers;

use Backend;
use Backend\Classes\FormField;
use Backend\Models\User;
use Backend\Widgets\Form;
use BackendMenu;
use Backend\Classes\Controller;
use Input;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Base\Controllers\Export;
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
            Mail::send('swordbros.booking_notify', $data, function ($message) use($data) {
                $message->to($data['send_email'], $data['booking']['first_name']);
                $message->subject($data['bookingModel']['email_title']);
            });
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
    public function toExcel(){
        $widget = $this->asExtension('ListController')->makeList();
        $widget->recordsPerPage= 1000;
        $widget->prepareVars();
        return Excel::download( new Export($widget->vars['records'] ), 'booking-request-'.date('Y-m-d-H-i-s').'.xlsx');
    }
    public function usersToExcel(){
        $usercontroler = new \RainLab\User\Controllers\Users();
        $widget = $usercontroler->asExtension('ListController')->makeList();
        $widget->recordsPerPage= 1000;
        $widget->model->select('id', 'username', 'email', 'created_at');
        $widget->prepareVars();
        return Excel::download( new Export($widget->vars['records'], ['id', 'first_name', 'last_name', 'email'] ), 'users-'.date('Y-m-d-H-i-s').'.xlsx');
    }
}
