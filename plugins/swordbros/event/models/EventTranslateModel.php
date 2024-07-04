<?php namespace Swordbros\Event\Models;

use Model;
use Swordbros\Base\models\BaseModel;

/**
 * Model
 */
class EventTranslateModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_translate';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
