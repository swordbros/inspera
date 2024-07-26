<?php namespace Swordbros\Event\Models;

use Model;
use October\Rain\Database\Factories\HasFactory;
use Swordbros\Base\models\BaseModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Controllers\EventCategory;

/**
 * Model
 */
class EventTypeModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;
    use HasFactory;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_types';
    public $translateClass = EventTranslateModel::class;
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    public $attachOne = [
        'thumb' => 'System\Models\File'
    ];

    public function categories()
    {
        return EventCategoryModel::where(['event_type_id'=>$this->id])->get();
    }
}
