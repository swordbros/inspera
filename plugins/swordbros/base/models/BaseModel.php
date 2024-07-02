<?php namespace Swordbros\Base\models;

use Model;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventTranslateModel;

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
            if(property_exists($row, 'translateClass')){
                Amele::localize_row($row);
            }
        });
        static::updated(function ($row) {
            if(property_exists($row, 'translateClass')){
                Amele::save_localize_row($row);
            }
        });
        static::created(function ($row) {
            if(property_exists($row, 'translateClass')){
                Amele::save_localize_row($row);
            }
        });
    }
}
