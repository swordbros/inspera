<?php namespace Swordbros\Booking\Controllers;

use Backend;
use Backend\Models\User;
use BackendMenu;
use Backend\Classes\Controller;
use Input;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Event\Models\EventModel;

class Booking extends BaseController
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

    public function listExtendQuery($query){
        $eventId = input('event_id');
        if ($eventId) {
              $query->where('event_id', $eventId);
        }
    }
}
