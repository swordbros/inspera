<?php namespace Swordbros\Base\Models;

use Site;

/**
 * Model
 */
class TranslateModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_translate';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    public function scopeSiteid($query)
    {
        $site_id = Site::getSiteIdFromContext();
        return $query->where('site_id', $site_id);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('siteid', function (\October\Rain\Database\Builder $builder) {
            $builder->siteid();
        });
    }
    public function getKey(){
        return $this->value;
    }

}
