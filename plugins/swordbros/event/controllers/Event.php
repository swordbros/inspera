<?php namespace Swordbros\Event\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Validation\Rules\In;
use Input;
use Lang;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventModel;
use Swordbros\Event\Models\EventZoneModel;

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
        $event_type_code = \Input::get('event_type_code');
        if($event_type_code){
            $this->formConfig = 'config_form_'.$event_type_code.'.yaml';
        }
        */
        parent::__construct();
        BackendMenu::setContext('Swordbros.Event', 'main-menu-item', 'side-menu-item2');
        $this->addCss('/plugins/swordbros/event/assets/css/swordbros.event.css');
        $this->addJs('/plugins/swordbros/event/assets/js/swordbros.event.js');
    }
    public function formExtendFields($form)
    {
        if ($this->action == 'create') {
            $form->model->event_type_id = input('event_type_id', 0);
        }
    }
    public function update($recordId = null, $context = null)
    {
        $metaData = Input::get('MetaModel');
        Amele::set_swordbros_meta($recordId, 'event', $metaData);
        $this->asExtension('FormController')->update($recordId, $context);
    }
    public static function emptyForm(){
        $event = new Event();
        $model = new EventModel();
        $model->attributes['start'] = \Input::get('date');
        $model->attributes['end'] = \Input::get('date');
        $model->attributes['event_type_id'] = \Input::get('event_type_id');
        $model->attributes['event_zone_id'] = \Input::get('event_zone_id');
        if($model->attributes['event_zone_id']){
            $eventZone = EventZoneModel::find($model->attributes['event_zone_id']);
            if($eventZone){
                $model->attributes['capacity'] = $eventZone->capacity;
            }
        }
        $event->layout = 'empty';

        $event->asExtension('FormController')->initForm($model, 'create');
        return $event->makePartial('blank_form');
    }

}
