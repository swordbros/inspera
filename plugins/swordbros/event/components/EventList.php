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
use Illuminate\Support\Collection;
use Swordbros\Event\Models\EventTagModel;
use Swordbros\Event\Models\EventZoneModel;

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
                'type' => 'dropdown',
                'emptyOption' => '---',
                'options' => self::getFeaturedTags()
            ],
            'venue' => [
                'title' => 'By venue',
                'type' => 'dropdown',
                'emptyOption' => '---',
                'options' => self::getVenues()
            ],
            'count' => [
                'title' => 'Max items',
                'description' => 'The most amount of items allowed, set 0 for unlimited',
                'default' => 0,
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
            if ($boxesBox->venue) {
                $this->setProperty('venue', $boxesBox->venue);
            }
            $this->setProperty('count', $boxesBox->count);
        }

        $this->page['events'] = $this->events = $this->getEvents();
    }

    public function getEvents(): Collection
    {
        $query = EventModel::published()
            ->future()
            ->with('event_zone', 'event_category', 'event_type', 'thumb');

        if ($featuredTag = $this->property('featuredTag')) {
            $query->whereHas('tagged_event', function ($q) use ($featuredTag) {
                $q->whereTag($featuredTag);
            });
        }

        if ($venueId = $this->property('venue')) {
            $query->filtered(['venues' => [$venueId]]);
        }

        $count = $this->property('count');
        if ($count > 0) {
            $query->limit($count);
        }

        return $query
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
                    'color' => $event->event_category?$event->event_category->color:'#000000',
                    'venue' => $event->event_zone?$event->event_zone->name:'',
                    'type' => $event->event_type?$event->event_type->name:'',
                    'category' => $event->event_category?$event->event_category->name:'',
                    'short' => $event->short
                ];
            });
    }

    public static function getFeaturedTags(): array
    {
        $tagOptions = (new EventTagModel)->getTagOptions();

        return array_map(fn($tag) => $tag[0], $tagOptions);
    }

    public static function getVenues(): array
    {
        return EventZoneModel::lists('name', 'id');
    }

    /**
     * TODO obsolete?
     */
    function onLoadJsonItems()
    {
        $page = input('page', 1);

        return EventModel::where(['status' => 1])->paginate(2, $page);
    }
}
