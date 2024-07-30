<?php

namespace Swordbros\Event\Components;

use App;
use Db;
use Input;
use Carbon\Carbon;
use Cms\Classes\Controller as CmsController;
use DateTime;
use Media\Classes\MediaLibrary;
use PHPUnit\TextUI\Help;

use OFFLINE\Boxes\Models\Page as BoxesPage;

use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventModel;
use Swordbros\Event\Models\EventTypeModel;
use Swordbros\Event\Models\EventZoneModel;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Cms\Facades\Cms;
use Lang;
use NotFoundException;
use Storage;

/**
 * BackendLink component
 */
class EventSearch extends ComponentBase
{
    public $events = [];
    public $filters = [];
    public $vars = [];

    public function componentDetails()
    {
        return [
            'name' => 'Swordbros Events Search',
            'description' => 'Search Swordbros Events.'
        ];
    }

    public function onClikDateNavigatorDay()
    {
        $html = '';
        $paginate = $this->eventSearchPagination();
        foreach ($paginate->items() as $key => $item) {
            $event = $item->toArray();
            $event['humanStart'] = Amele::humanDate($event['start'], 'd.m');
            $event['humanYear'] = Amele::humanDate($event['start'], 'Y');
            $event['humanHour'] = Amele::humanDate($event['start'], 'H:i');
            $event['color'] = $event['event_type'] ? $event['event_type']['color'] : '';
            $event['thumb_image'] = MediaLibrary::url($event['thumb']);

            $html .= $this->renderPartial('event-search/item', $event);
        }
        return [
            '#search-result' => $html
        ];
    }

    public function onRun()
    {
        $this->page['mediaUrl'] = MediaLibrary::url('/');
        $this->page['title'] = __('event.events');
        $data['eventTypes'] = EventTypeModel::all();

        $data['eventZones'] = EventZoneModel::all();
        $data['audiences'] = Amele::getAudiences();

        $data['days'] = [];
        $date = \Input::get('date');
        if (empty($date)) {
            $year = date('Y');
            $month = date('m');
        } else {
            [$year, $month] = explode('-', $date);
        }
        $data['year'] =  $year;
        $data['month'] =  $month;
        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $data['start'] =  $firstDayOfMonth->format('Y-m-d H:i:s');

        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
        $data['end'] =  $lastDayOfMonth->format('Y-m-d H:i:s');
        for ($date = $firstDayOfMonth->copy(); $date->lte($lastDayOfMonth); $date->addDay()) {
            $data['days'][] = [
                'date' => $date->format('Y-m'),
                'start' => $date->format('Y-m-d 00:00:00'),
                'end' => $date->format('Y-m-d 23:59:09'),
                'monthName' => __('event.plugin.month-' . $date->format('d')),
                'yearNumber' => $date->format('Y'),
                'monthNumber' => $date->format('m'),
                'dayNumber' => $date->format('d'),
                'weekend' => $date->isWeekend(),
                'class' => $date->isWeekend() ? 'day-weekend' : '',
                'hasEvent' => EventModel::where([
                    ['status', '=', 1],
                    ['start', '>=', $date->format('Y-m-d 00:00:00')],
                    ['end', '<=', $date->format('Y-m-d 23:59:09')]
                ])->count()
            ];
        }

        $data['todayStart'] = date('Y-m-d 00:00:00');
        $data['todayEnd'] = date('Y-m-d 23:59:09');

        $tomorrow = new DateTime();
        $tomorrow->modify('+1 day');

        $data['tomorrowStart'] = $tomorrow->format('Y-m-d 00:00:00');
        $data['tomorrowEnd'] = $tomorrow->format('Y-m-d 23:59:09');


        $firstDayOfWeek = new DateTime();
        $firstDayOfWeek->setISODate((int)$firstDayOfWeek->format('o'), (int)$firstDayOfWeek->format('W'), 1);
        $lastDayOfWeek = new DateTime();
        $lastDayOfWeek->setISODate((int)$lastDayOfWeek->format('o'), (int)$lastDayOfWeek->format('W'), 7);
        $data['weekStart'] =  $firstDayOfWeek->format('Y-m-d 00:00:00');
        $data['weekEnd'] =  $lastDayOfWeek->format('Y-m-d 23:59:09');

        $now = new DateTime();
        $startOfWeekend = clone $now;
        $startOfWeekend->modify('next saturday');
        if ($startOfWeekend->format('N') != 6) {
            $startOfWeekend->modify('last saturday');
        }
        $endOfWeekend = clone $startOfWeekend;
        $endOfWeekend->modify('next sunday');
        $data['weekendStart'] = $startOfWeekend->format('Y-m-d 00:00:00');
        $data['weekendEnd'] = $endOfWeekend->format('Y-m-d 23:59:09');

        $today = new DateTime();
        $data['monthStart'] = $today->format('Y-m-01 00:00:00');
        $data['monthEnd'] = $today->format('Y-m-t 23:59:09');

        $currentMonthFirstDay = Carbon::create($year, $month, 1);
        $data['datePrev'] = date('Y-m', strtotime('-1 month', strtotime($currentMonthFirstDay)));
        $data['dateNext'] = date('Y-m', strtotime('+1 month', strtotime($currentMonthFirstDay)));

        $this->page['searchIndex'] = $this->renderPartial('event-search/index', $data);
        $this->page['searchFilters'] = $this->renderPartial('event-search/filters', $data);
    }

    function onLoadJsonItems()
    {
        $page = input('page', 1);
        return EventModel::where(['status' => 1])->paginate(2, $page);
    }

    private function getMonthDays(): array
    {
        $params = Input::get('params');

        $data = [];
        $date = $params['month'];

        if (empty($date)) {
            $year = date('Y');
            $month = date('m');
        } else {
            [$year, $month] = explode('-', $date);
        }

        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
        for ($date = $firstDayOfMonth->copy(); $date->lte($lastDayOfMonth); $date->addDay()) {
            $data['days'][] = [
                'number' => $date->format('j'),
                'isWeekend' => $date->isWeekend(),
                'isToday' => $date->isToday(),
                'isTomorrow' => $date->isTomorrow(),
                'hasEvent' => DB::table('swordbros_event_events')->Where([
                    ['status', '=', 1],
                    ['start', '<=', $date->format('Y-m-d 23:59:09')],
                    ['end', '>=', $date->format('Y-m-d 00:00:00')]
                ])->exists(),
            ];
        }

        return $data['days'];
    }
    private function eventSearchPagination()
    {
        $params = self::searchParameters();
        $query = \Db::table('swordbros_event_search')
            ->select(['id', 'start'])
            ->distinct('id')
            ->where([['status', '=', 1]]);

        if ($params['event_type_ids']) {
            $query->whereIn('event_type_id', $params['event_type_ids']);
        }
        if ($params['event_zone_ids']) {
            $query->whereIn('event_zone_id', $params['event_zone_ids']);
        }
        if ($params['event_category_ids']) {
            $query->whereIn('event_category_id', $params['event_category_ids']);
        }
        if ($params['audiences']) {
            $query->whereIn('audience', $params['audiences']);
        }
        if ($params['start']) {
            $query->where([['start', '>=', $params['start']]]);
        }
        if ($params['end']) {
            $query->where([['end', '<=', $params['end']]]);
        }
        $query->orderByDesc('start');
        $paginate = $query->paginate(100, $params['page']);

        if ($paginate->items()) {
            foreach ($paginate->items() as $key => $item) {
                $event = EventModel::find($item->id);
                $event->attributes['event_zone'] = $event->event_zone;
                $event->attributes['event_type'] = $event->event_type;
                $event->attributes['event_category'] = $event->event_category;
                $paginate->offsetSet($key, $event);
            }
        }
        return ($paginate);
    }

    private static function searchParameters()
    {
        $dateType = \Input::get('dateType', false);
        $zone_ids = \Input::get('zone', false);
        $category_ids = \Input::get('category', false);
        $audience_ids = \Input::get('audience', false);
        $start = \Input::get('start', false);
        $end = \Input::get('end', false);
        return [
            'start' => $start,
            'end' => $end,
            'event_type_ids' => false,
            'event_zone_ids' => $zone_ids,
            'event_category_ids' => $category_ids,
            'audiences' => $audience_ids,
            'page' => \Input::get('page', 1),
        ];
    }

    /**
     * Single entry point for listing updates
     */
    public function onGetEvents()
    {
        $this->events = $this->getEvents();

        $result['events'] = $this->events
            ->map(function (EventModel $event) {
                $path = $event->thumb?->getThumb(546, 402, ['mode' => 'crop']);
                return [
                    'title' => $event->title,
                    'url' => $event->url,
                    'thumb' => $path ?: 'https://place-hold.it/546x400',
                    'start' => $event->start,
                    'end' => $event->end,
                    'color' => $event->color,
                    'venue' => $event->event_zone->name,
                    'type' => $event->event_type->name,
                    'category' => $event->event_category->name,
                ];
            });

        if (Input::get('shouldChangeMonth')) {
            $result['filters'] = $this->getFilters();
            $result['daysData'] = $this->getMonthDays();
        }

        return $result;
    }

    public function onGetVars()
    {
        App::setLocale('en');
        $homePage = BoxesPage::whereSlug('home')->first();

        return [
            'breadcrumbs' => [
                [
                    'url' => $homePage?->url,
                    'title' => $homePage?->name
                ],
                [
                    'url' => null,
                    'title' => Lang::get('swordbros.themeextender::lang.components.calendar')
                ]
            ],
            'noEventsText' => Lang::get('swordbros.themeextender::lang.components.no_events_text'),
        ];
    }

    private function getFilters()
    {
        $this->filters = [
            'types' => $this->getFilter('event_type'),
            'venues' => $this->getFilter('event_zone'),
            'categories' => $this->getFilter('event_category')
        ];
        return $this->filters;
    }

    private function getFilter(string $relationName): array
    {
        return [
            'title' => Lang::get("swordbros.themeextender::lang.components.{$relationName}"),
            'options' => array_values(
                $this->events
                    ->map(function (EventModel $e) use ($relationName) {
                        return [
                            'value' => $e->$relationName->id,
                            'label' => $e->$relationName->name
                        ];
                    })
                    ->unique('value')
                    ->toArray()
            )
        ];
    }

    private function getEvents()
    {
        $params = Input::get('params');

        if (!isset($params['month'])) {
            // TODO maybe not throw but use current month (frontend side?)
            throw new NotFoundException('Month is a required parameter');
        }

        return EventModel::published()
            // ->select(...)
            ->with('event_zone', 'event_category', 'event_type', 'thumb')
            ->filtered($params)
            ->orderBy('start')
            ->get();
    }
}
