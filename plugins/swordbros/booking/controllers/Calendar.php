<?php namespace Swordbros\Booking\Controllers;

use BackendMenu;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Booking\models\BookingModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Controllers\Event;
use Swordbros\Event\Models\EventModel;


class Calendar extends BaseController
{
    public $implement = [
        \Backend\Behaviors\ListController::class
    ];

    public $listConfig = 'config_list.yaml';
    public $requiredCss = [
        '/plugins/swordbros/event/assets/css/swordbros.event.main.css',
    ];
    public $requiredJs = [
        '/plugins/swordbros/event/assets/js/swordbros.event.main.js',
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
        $this->vars['events'] = $this->eventsToCalender();
    }
    private function eventsToCalender(){
        $rows = EventModel::all();
        $events = [];
        if(!$rows->isEmpty()){
            foreach ($rows as $row){
                $total = BookingModel::getTotalEventBooking($row->id);
                $capacity = BookingModel::getCapacityEventBooking($row->id);
                $events[] = [
                    'title'=>$row->title." ($total/$capacity)",
                    'start'=>$row->start,
                    'end'=>$row->end,
                    'borderColor'=>$row->color,
                    'total'=> $total,
                    'event_view_url'=> \Backend::url('swordbros/event/event/update',['id'=>$row->id]),
                    'event_booking_url'=> \Backend::url('swordbros/booking/booking').'?event_id='.$row->id,
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
}
