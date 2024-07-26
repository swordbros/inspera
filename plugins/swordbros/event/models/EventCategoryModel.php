<?php namespace Swordbros\Event\Models;

use Model;
use RainLab\User\Models\User;
use Swordbros\Base\Models\BaseModel;
use Swordbros\Base\Controllers\Amele;

/**
 * Model
 */
class EventCategoryModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_categories';
    public $translateClass = EventTranslateModel::class;

    /**
     * @var array rules for validation.
     */
    public $rules = [];
    public $belongsTo = [
        'event_type' => EventTypeModel::class,
    ];
    public $attachOne = [
        'thumb' => 'System\Models\File'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public static function getEventTypeIdOptions(){
        $result = [];
        foreach (EventTypeModel::all() as $item) {
            Amele::localize_row($item);
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
    public function getEventTypeIds(){
        return self::getEventTypeIdOptions();
    }
    public function scopeisEventTypeIds($query, $value)
    {
        if ($value) {
            $query->where('event_type_id', $value);
        }
    }
}
