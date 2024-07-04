<?php namespace Swordbros\Event\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Lang;
use Swordbros\Event\Models\EventModel;

class Event extends Controller
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
        BackendMenu::setContext('Swordbros.Event', 'main-menu-item', 'side-menu-item2');
        $this->addCss('/plugins/swordbros/event/assets/css/swordbros.event.css');

    }

    public static function fromComponent(){
        $event = new Event();
        $model = new EventModel();

        $event->initForm($model);
        $event->formRender();
    }
    public static function emptyForm(){
        $event = new Event();
        $model = new EventModel();
        $event->layout = 'empty';
        $event->asExtension('FormController')->initForm($model);
        return $event->makePartial('blank_form');
    }

}
