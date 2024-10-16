<?php namespace Swordbros\Event\Models;

use Model;
use October\Rain\Database\Relations\HasOne;
use Swordbros\Base\models\BaseModel;
use Swordbros\Base\Controllers\Amele;

/**
 * Model
 */
class EventTagModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_tags';

    /**
     * @var array rules for validation.
     */

    public $rules = [
    ];
    protected $fillable = ['event_id', 'tag', 'sort_order'];

    // EventModel ile ilişki
    public $belongsTo = [
        'event' => 'Swordbros\Event\Models\EventModel'
    ];
    public function getTagOptions(){
        return[
            'hero'=>[ __('event.tags.showonhero'), '#ff5e00'],
            'featured'=>[__('event.tags.featured'), '#cd2345'],
            'bestseller'=>[__('event.tags.bestseller'), '#2acd23'],
            'recommended'=>[__('event.tags.recommended'), '#2325cd'],
        ];
    }
    public function scopeisTag($query, $value)
    {
        if ($value) {
            $query->where('tag', $value);
        }
    }

}
