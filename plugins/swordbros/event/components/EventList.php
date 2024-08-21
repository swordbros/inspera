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
use Swordbros\Event\Models\EventTagModel;

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

    public function defineProperties()
    {
        return [
            'featuredTag' => [
                'title' => 'Featured tag',
                // 'description' => 'The most amount of todo items allowed',
                'type' => 'dropdown',
                'options' => self::getFeaturedTags()
            ],
            'count' => [
                'title' => 'Max items',
                'description' => 'The most amount of items allowed',
                'default' => 4,
                'type' => 'string',
                'validation' => [
                    'regex' => [
                        'message' => 'The Max Items property can contain only numeric symbols.',
                        'pattern' => '^[0-9]+$'
                    ]
                ]
            ]
        ];
    }

    public function onRun()
    {
        if ($this->methodExists('getBoxesBox')) {
            $boxesBox = $this->getBoxesBox();
            if ($boxesBox->featured_tag) {
                $this->setProperty('featuredTag', $boxesBox->featured_tag);
            }
            $this->setProperty('count', $boxesBox->count);
        }

        $featuredTag = $this->property('featuredTag');
        $this->page['events'] = $this->events = EventModel::whereHas('tagged_event', function ($q) use ($featuredTag) {
            $q->whereTag($featuredTag);
        })
            ->published()
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
                    'color' => $event->event_category->color,
                    'venue' => $event->event_zone->name,
                    'type' => $event->event_type->name,
                    'category' => $event->event_category->name,
                    'short' => $event->short
                ];
            });
    }

    public static function getFeaturedTags(): array
    {
        $tagOptions = (new EventTagModel)->getTagOptions();

        return array_map(fn($tag) => $tag[0], $tagOptions);
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
