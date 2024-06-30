<?php namespace Swordbros\Event\Controllers;


use BackendMenu;
use Input;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Event\Models\EventModel;


class Calendar extends BaseController{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $requiredCss = [
        '/plugins/swordbros/event/assets/css/swordbros.event.main.css',
    ];
    public $requiredJs = [
        '/plugins/swordbros/event/assets/js/swordbros.event.main.js',
        '/plugins/swordbros/assets/fullcalendar/index.global.js'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Swordbros.Event', 'main-menu-item', 'side-menu-item');
    }
    public function index(){
        $this->vars['services'] = Amele::services();
        $this->vars['places'] = Amele::places();
        $this->vars['events'] = $this->eventsToCalender();
        $this->formConfig = $this->makeConfig('$/swordbros/event/models/eventmodel/fields.yaml');
        $this->asExtension('FormController')->create();

    }
    private function eventsToCalender(){
        $rows = EventModel::all();
        $events = [];
        if(!$rows->isEmpty()){
            foreach ($rows as $row){
                $events[] = [
                    'title'=>$row->title,
                    'start'=>$row->start,
                    'end'=>$row->end,
                    'borderColor'=>$row->color,
                    'event_url'=> \Backend::url('swordbros/booking/booking').'?event_id='.$row->id,

                    /*'backgroundColor'=>$row->color,
                    'borderColor'=>$row->color,
                    'textColor'=>$row->color,*/
                ];
            }
        }
        return json_encode($events, JSON_UNESCAPED_UNICODE);
    }
    public function onGetEventTypeForm()
    {
        $content = Event::emptyForm();

        return ['#listBulkActions' => $content];
    }
    public function onSave(){
        $EventModel = Input::get('EventModel');
        if($EventModel){
           $event = new EventModel();
           foreach($EventModel as $key=>$value){
               $event->$key = $value;
           }
           $event->save();
        }
    }
}
