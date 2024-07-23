<?php namespace Swordbros\Event\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Validation\Rules\In;
use Input;
use Lang;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventModel;
use Swordbros\Event\Models\EventReviewModel;
use Swordbros\Event\Models\EventZoneModel;

class Event extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        /**
        $event_type_code = \Input::get('event_type_code');
        if($event_type_code){
            $this->formConfig = 'config_form_'.$event_type_code.'.yaml';
        }
        */
        parent::__construct();
        BackendMenu::setContext('Swordbros.Event', 'main-menu-item', 'side-menu-item2');
        $this->addCss('/plugins/swordbros/event/assets/css/swordbros.event.css');
        $this->addJs('/plugins/swordbros/event/assets/js/swordbros.event.js');
    }
    public function eventSearch(){
        $params = self::searchParameters();
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
        $paginate = $query->paginate(2, $params['page']);
        $data['events'] = [];
        if($paginate->items()){
            foreach ($paginate->items() as $key=>$item){
                $event = EventModel::find($item->id);
                $event->attributes['event_zone'] = $event->event_zone;
                $event->attributes['event_type'] = $event->event_type;
                $event->attributes['event_category'] = $event->event_category;
                $data['events'][] = $event;
            }
        }
        return $this->makePartial('event-search/filters', $data);
    }
    private static function searchParameters(){
        $dateType = \Input::get('dateType', false);
        $zone_ids = \Input::get('zone', false);
        $category_ids = \Input::get('category', false);
        $audience_ids = \Input::get('audience', false);
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:09');
        return [
            'start'=>$start,
            'end'=>$end,
            'event_type_ids'=>false,
            'event_zone_ids'=>$zone_ids,
            'event_category_ids'=>$category_ids,
            'audiences'=>$audience_ids,
            'page'=>\Input::get('page', 1),
        ];
    }
    public function formExtendFields($form)
    {
        if ($this->action == 'create') {
            $form->model->capacity = input('capacity', 0);
            $form->model->event_type_id = input('event_type_id', 0);
        }
    }
    public function update($recordId = null, $context = null)
    {
        $metaData = Input::get('MetaModel');
        Amele::set_swordbros_meta($recordId, 'event', $metaData);
        $this->asExtension('FormController')->update($recordId, $context);
    }
    public static function emptyForm(){
        $event = new Event();
        $model = new EventModel();
        $model->attributes['start'] = \Input::get('date');
        $model->attributes['end'] = \Input::get('date');
        $model->attributes['event_type_id'] = \Input::get('event_type_id');
        $model->attributes['event_zone_id'] = \Input::get('event_zone_id');
        if($model->attributes['event_zone_id']){
            $eventZone = EventZoneModel::find($model->attributes['event_zone_id']);
            if($eventZone){
                $model->attributes['capacity'] = $eventZone->capacity;
            }
        }
        $event->layout = 'empty';

        $event->asExtension('FormController')->initForm($model, 'create');
        return $event->makePartial('blank_form');
    }

    public function onEventReviewStatusChange(){

        $status = Input::get('status', false);
        if($status===false){
            \Flash::error('tamamdır');
        } else {
            $review = EventReviewModel::find(Input::get('review_id', 0));
            if($review){
                if($status=="1"){
                    $review->status = 1;
                    $review->save();
                    \Flash::success(trans('swordbros.event::plugin.event_to_enabled'));
                    return ['#status-icons-'.$review->id=>'<button type="button" class="oc-icon-ban btn-icon" data-request-data="{status:0,review_id:'.$review->id.'}" data-request="onEventReviewStatusChange"></button>'];


                } else if($status==="0"){
                    $review->status = 0;
                    \Flash::warning(trans('swordbros.event::plugin.event_to_disabled'));
                    $review->save();
                    return ['#status-icons-'.$review->id=>'<button type="button" class="oc-icon-check btn-icon" data-request-data="{status:1,review_id:'.$review->id.'}" data-request="onEventReviewStatusChange"></button>'];
                }
            }
        }
    }
}
