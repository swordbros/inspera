<?php namespace Swordbros\Event\Models;

use Model;
use Swordbros\Base\models\BaseModel;

/**
 * Model
 */
class CalendarModel extends BaseModel
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
    ];

}
