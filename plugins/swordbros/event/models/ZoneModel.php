<?php namespace Swordbros\Event\Models;

use Model;
use Swordbros\Base\models\BaseModel;
use Swordbros\Base\Controllers\Amele;

/**
 * Model
 */
class ZoneModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_zones';

    /**
     * @var array rules for validation.
     */

    public $rules = [
    ];

}
