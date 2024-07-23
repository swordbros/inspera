<?php

namespace Swordbros\Event\Components;

use Media\Classes\MediaLibrary;
use Response;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventModel;
use System;
use Backend;
use Cms\Classes\ComponentBase;

/**
 * BackendLink component
 */
class EventList extends ComponentBase
{
    public $events = [];
    public $vars = [];
    public function componentDetails()
    {
        return [
            'name' => 'Swordbros Events List',
            'description' => 'Show Swordbros Events.'
        ];
    }

    public function onRun()
    {
        $this->page['mediaUrl'] = MediaLibrary::url('/');
        $this->page['title'] = __('event.events');
        $this->page['events'] = $this->events = EventModel::where(['status' => 1])->get();
    }
    /**function onLoadAjaxPartial()
    {
        $data['page'] = \Input::get('page', 1);
        $data['time'] = time();
        return [
            '#dynamic-content' => $this->renderPartial('event/list-item', $data)
        ];
    }*/
    function onLoadJsonItems()
    {
        $page = input('page', 1);

        return EventModel::where(['status' => 1])->paginate(2, $page);
    }
}
