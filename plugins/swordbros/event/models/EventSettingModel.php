<?php namespace Swordbros\Event\Models;

use Model;
use October\Rain\Database\Relations\HasOne;
use Swordbros\Base\models\BaseModel;
use Swordbros\Base\Controllers\Amele;

/**
 * Model
 */
class EventSettingModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_settings';

    /**
     * @var array rules for validation.
     */

    public $rules = [
    ];
    protected $fillable = [];

}
