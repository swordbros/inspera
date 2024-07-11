<?php namespace Swordbros\Event\Models;

use Model;
use October\Rain\Database\Relations\HasOne;
use Swordbros\Base\models\BaseModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Controllers\EventReview;

/**
 * Model
 */
class EventReviewModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_reviews';

    /**
     * @var array rules for validation.
     */
    public $belongsTo = [
        'event' => 'Swordbros\Event\Models\EventModel',
        'user' => 'RainLab\\User\\Models\\User'
    ];
    public $rules = [
    ];
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($row) {
            self::setEventRating($row);
        });
        static::created(function ($row) {
            self::setEventRating($row);
        });
    }
    private static function setEventRating($row){
        $event = EventModel::find($row->event_id);
        if($event){
            $event->rating = EventReviewModel::where(['event_id'=>$row->event_id, 'status'=>1])->avg('stars');
            $event->save();
        }
    }
}
