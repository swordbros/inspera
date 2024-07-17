<?php namespace Swordbros\Event\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Swordbros\Event\Models\EventSettingModel;

class EventSetting extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
    ];

    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Swordbros.Event', 'main-menu-item', 'side-menu-item8');
    }
    public function index(){
        $eventSetting = EventSettingModel::first();
        if($eventSetting){
            return $this->asExtension('FormController')->update( $eventSetting->id, 'update');
        } else {
            return $this->asExtension('FormController')->create();
        }
    }

    public function onSave(){
        $eventSetting = EventSettingModel::first();
        if(empty($eventSetting)){
            $eventSetting = new EventSettingModel();
        }
        $eventSettingModel = \Input::get('EventSettingModel');
        $eventSetting->alert_emails = isset($eventSettingModel['alert_emails'])?$eventSettingModel['alert_emails']:'';
        $eventSetting->save();
    }
}
