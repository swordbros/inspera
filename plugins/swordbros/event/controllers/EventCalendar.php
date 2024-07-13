<?php namespace Swordbros\Event\Controllers;


use BackendMenu;
use Illuminate\Validation\Rules\In;
use Input;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Event\Models\EventModel;


class EventCalendar extends BaseController{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $requiredCss = [
        '/plugins/swordbros/event/assets/css/swordbros.event.css',
    ];
    public $requiredJs = [
        '/plugins/swordbros/event/assets/js/swordbros.event.js',
        '/plugins/swordbros/assets/fullcalendar/index.global.js'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Swordbros.Event', 'main-menu-item', 'side-menu-item');
    }
    public function index(){
        $this->vars['event_types'] = Amele::eventTypes();
        $this->vars['event_zones'] = Amele::eventZones();
        $this->vars['events'] = '[]';//$this->eventsToCalender();
        $this->vars['getfilteredevents_url'] = \Backend::url('swordbros/event/eventcalendar/getfilteredevents');
        $this->formConfig = $this->makeConfig('$/swordbros/event/models/eventmodel/fields.yaml');
        $this->asExtension('FormController')->create();
    }
    public function getFilteredEvents(){
        $filter['start'] = Input::get('start', false);
        $filter['end'] = Input::get('end', false);
        return \Response::json($this->eventsToCalender($filter)) ;
    }

    private function eventsToCalender($filter=[]){
        $query = EventModel::query();
        if(isset($filter['start']) && $filter['start']){
            $query->where([['start', '>=', $filter['start']]]);
        }
        if(isset($filter['end']) && $filter['end']){
            $query->where([['end', '<=', $filter['end']]]);
        }

        $rows = $query->get();
        $events = [];
        if(!$rows->isEmpty()){
            foreach ($rows as $row){
                $events[] = [
                    'title'=>$row->title,
                    'start'=>$row->start,
                    'end'=>$row->end,
                    'backgroundColor'=> $row->color?$row->color:'red',
                    'classNames'=> [$row->status?'swordbros-event active':'swordbros-event passive'],
                    'event_view_url'=> \Backend::url('swordbros/event/event/update',['id'=>$row->id]),
                    'event_booking_url'=> \Backend::url('swordbros/booking/booking').'?event_id='.$row->id,
                ];
            }
        }
        return $events;
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
           $saveResult = $event->save();
           if($saveResult){
               $metaData =  Input::get('MetaModel');
               Amele::set_swordbros_meta($event->id, 'event', $metaData);
           }
        }
    }
}
