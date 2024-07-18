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
    protected $translated;
    protected static function boot()
    {
        parent::boot();
        static::fetched(function ($row) {
            Amele::localize_row($row);
        });
        static::updated(function ($row) {
            Amele::update_localize_row($row);
        });
        static::creating(function ($row) {
            if(isset($row->code) && isset($row->name)){
                if(empty($row->code)){
                    $row->code = Str($row->name)->slug();
                }
            }
            dd($row);
        });
        static::created(function ($row) {
            Amele::create_localize_row($row);
        });
    }
    function setTranslated(){
        $this->translated = true;
    }
    function isTranslated(){
        return $this->translated;
    }
}
