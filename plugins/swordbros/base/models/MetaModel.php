<?php namespace Swordbros\Base\models;

use Model;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventTranslateModel;

/**
 * Model
 */
class MetaModel extends BaseModel
{
    public $table = 'swordbros_metas';
    public $translateClass = EventTranslateModel::class;

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    //public $attributes = ['id'=>null,'site_id'=>null,'module'=>null,'meta_key'=>null,'meta_value'=>null];
    function __construct(array $attributes = [], $table=null){
        $this->table = $table;
        $this->fillable = array_keys($attributes);
        parent::__construct($attributes);
    }
}
