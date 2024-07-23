<?php namespace Swordbros\Booking\Controllers;

use Backend;
use Backend\Classes\FormField;
use Backend\Models\User;
use Backend\Widgets\Form;
use BackendMenu;
use Backend\Classes\Controller;
use Input;
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
        /**
        $event_type = \Input::get('event_type');
        if($event_type){
            $this->formConfig = 'config_form_'.$event_type.'.yaml';
        }
        */
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
