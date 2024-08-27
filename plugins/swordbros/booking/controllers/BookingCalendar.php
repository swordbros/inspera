<?php namespace Swordbros\Booking\Controllers;

use BackendMenu;
use Input;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Booking\models\BookingModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Controllers\Event;
use Swordbros\Event\Models\EventModel;


class BookingCalendar extends BaseController
{
    public $implement = [
        \Backend\Behaviors\ListController::class
    ];

    public $listConfig = 'config_list.yaml';
    public $requiredCss = [
        '/plugins/swordbros/event/assets/css/swordbros.event.css',
    ];
    public $requiredJs = [
        '/plugins/swordbros/event/assets/js/swordbros.event.js',
        '/plugins/swordbros/event/assets/fullcalendar/index.global.js'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Swordbros.Booking', 'main-menu-item', 'side-menu-item');
    }
    public function index(){
        $this->vars['services'] = Amele::eventTypes();
        $this->vars['places'] = Amele::eventZones();
        $this->vars['events'] = '[]';
        $this->vars['getfilteredevents_url'] = \Backend::url('swordbros/booking/bookingcalendar/getfilteredevents');
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
                $total = BookingModel::getTotalEventBooking($row->id);
                $capacity = BookingModel::getCapacityEventBooking($row->id);
                $events[] = [
                    'title'=>$row->title." ($total/$capacity)",
                    'start'=>$row->start,
                    'end'=>$row->end,
                    'backgroundColor'=> $row->event_type?$row->event_type->color:'red',
                    'classNames'=> [$row->status?'swordbros-event active':'swordbros-event passive'],
                    'total'=> $total,
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
}
