<?php

namespace Swordbros\Event\Components;

use Media\Classes\MediaLibrary;
use Response;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventModel;
use System;
use Backend;
use Carbon\Carbon;
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
        $this->page['events'] = $this->events = EventModel::published()
            ->future()
            ->with('event_zone', 'event_category', 'event_type', 'thumb')
            ->orderBy('start')
            ->get()
            ->map(function (EventModel $event) {
                $path = $event->thumb?->getThumb(546, 402, ['mode' => 'crop']);
                $startDate = Carbon::parse($event->start);
                $endDate = Carbon::parse($event->end);
                return [
                    'title' => $event->title,
                    'url' => $event->url,
                    'thumb' => $path ?: 'https://place-hold.it/546x400',
                    'startDate' => $startDate->format('d.m'),
                    'endDate' => $startDate->format('d.m') === $endDate->format('d.m') ? null : $endDate->format('d.m'),
                    'year' => $startDate->format('Y'),
                    'time' => $startDate->format('H:i'),
                    'color' => $event->event_type->color,
                    'venue' => $event->event_zone->name,
                    'type' => $event->event_type->name,
                    'category' => $event->event_category->name,
                    'short' => $event->short
                ];
            });
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
