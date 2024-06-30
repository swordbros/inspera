<?php namespace Swordbros\Booking\Controllers;

use Backend;
use Backend\Models\User;
use BackendMenu;
use Backend\Classes\Controller;
use Input;
use Swordbros\Event\Models\EventModel;

class Booking extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
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
        BackendMenu::setContext('Swordbros.Booking', 'main-menu-item', 'side-menu-item3');
        $eventId = input('event_id');
        if ($eventId) {
            $eventModel = EventModel::find($eventId);
            if($eventModel){
                $this->pageTitle = $eventModel->title;
            }
        }
    }

    public function onUserDropDownChange(){
        $result = [];
        $BookingModel = Input::get('BookingModel');
        $user_id = isset($BookingModel['user_id'])?$BookingModel['user_id']:0;
        $row = User::find($user_id);
        $user = [];
        if(empty($row)){
            $row = new User();
            foreach($row->getFillable() as $field){
                $user[$field] = '';
            }
        } else {
            foreach($row->getAttributes() as $field=>$value){
                $user[$field] = $value;
            }
        }
        foreach($user as $key=>$value){
            $result['#Form-field-BookingModel-'.$key] =  $value;
        }
        return ['fields' => $result];
    }
    public function listExtendQuery($query){
        $eventId = input('event_id');
        if ($eventId) {
              $query->where('event_id', $eventId);
        }
    }
}
