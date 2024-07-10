<?php namespace Swordbros\Booking\Controllers;

use BackendMenu;
use Response;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Booking\models\BookingModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Controllers\Event;
use Swordbros\Event\Models\EventModel;


class Api extends BaseController
{
    public static function search(){
        $params = self::searchParanters();
        $query = \Db::table('swordbros_event_search')
            ->select('id')
            ->distinct('id')
        ->where([['status', '=', 1]]);
        if($params['event_type_ids']){
            $query->whereIn('event_type_id', $params['event_type_ids']);
        }
        if($params['event_zone_ids']){
            $query->whereIn('event_zone_id', $params['event_zone_ids']);
        }
        if($params['event_category_ids']){
            $query->whereIn('event_category_id', $params['event_category_ids']);
        }
        if($params['audiences']){
            $query->whereIn('audience', $params['audiences']);
        }
        if($params['start']){
            $query->where([['start','>=', $params['start']]]);
        }
        if($params['end']){
            $query->where([['end','<=', $params['end']]]);
        }
        if($params['text']){
            $query->where([['text','LIKE', "%".$params['text']."%"]]);
        }
        if($params['sort']){
            if(in_array($params['sort'], ['id', 'text', 'start', 'end'])){
                if($params['dir']=='DESC'){
                    $query->orderByDesc($params['sort']);
                } else{
                    $query->orderBy($params['sort']);
                }
            }
        }

        $paginate = $query->paginate(2, $params['page']);
        if($paginate->items()){
            foreach ($paginate->items() as $key=>$item){
                $event = EventModel::find($item->id);

                $event->attributes['event_zone'] = $event->event_zone;
                $event->attributes['event_type'] = $event->event_type;
                $event->attributes['event_category'] = $event->event_category;
                $paginate->offsetSet($key, $event) ;
            }
        }
        return Response::json($paginate);
    }
    private static function searchParanters(){
        $input_type_id = \Input::get('type_id', false);
        $input_zone_id = \Input::get('zone_id', false);
        $input_category_id = \Input::get('category_id', false);
        $input_audience = \Input::get('audience', false);
        return [
            'event_type_ids'=> $input_type_id?explode(',', $input_type_id):[],
            'event_zone_ids'=> $input_zone_id?explode(',', $input_zone_id):[],
            'event_category_ids'=> $input_category_id?explode(',', $input_category_id):[],
            'audiences'=> $input_audience?explode(',', $input_audience):[],
            'start'=> \Input::get('start', false),
            'end'=> \Input::get('end', false),
            'text'=>\Input::get('text', false),
            'sort'=>\Input::get('sort', 'id'),
            'dir'=>\Input::get('dir', 'DESC'),
            'page'=>\Input::get('page', 1),
        ];
    }
}
