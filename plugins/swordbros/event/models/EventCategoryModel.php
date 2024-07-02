<?php namespace Swordbros\Event\Models;

use Model;
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

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    protected static function boot()
    {
        parent::boot();

    }
}
