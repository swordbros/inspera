<?php namespace Swordbros\Base\models;

use Model;
use Swordbros\Base\Controllers\Amele;

/**
 * Model
 */
class BaseModel extends Model
{
    use \October\Rain\Database\Traits\Validation;

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
