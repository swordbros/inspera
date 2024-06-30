<?php namespace Swordbros\Event\Models;

use Model;
use Swordbros\Base\Models\BaseModel;
use Swordbros\Base\Controllers\Amele;

/**
 * Model
 */
class CategoryModel extends BaseModel
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
        static::fetched(function ($row) {
            Amele::localize_row($row);
        });
        static::updated(function ($row) {
            Amele::save_localize_row($row);
        });
        static::created(function ($row) {
            Amele::save_localize_row($row);
        });
    }
}
