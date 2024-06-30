<?php namespace Swordbros\Event\Models;

use Model;
use Swordbros\Base\models\BaseModel;
use Swordbros\Base\Controllers\Amele;

/**
 * Model
 */
class EventModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_events';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title' => ['required'],
        'event_zone_id' => ['required'],
        'event_category_id' => ['required'],
        'event_type_id' => ['required'],
        'start' => ['required'],
        'end' => ['required'],

    ];

    public $belongsTo = [
        'event_zone' => ZoneModel::class,
        'event_category' => CategoryModel::class,
        'event_type' => TypeModel::class,

    ];
    protected static function boot()
    {
        parent::boot();
        static::fetched(function ($row) {
            $row->images = json_decode($row->images);
            //$row->detail_url = url('event', ['id'=>$row->id]) ;
        });
    }
    public function getEventCategoryIdOptions(){
        $result = [];
        foreach (CategoryModel::all() as $item) {
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
    public function getEventTypeIdOptions(){
        $result = [];
        foreach (TypeModel::all() as $item) {
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
    public function getEventZoneIdOptions(){
        $result = [];
        foreach (ZoneModel::all() as $item) {
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }

}
