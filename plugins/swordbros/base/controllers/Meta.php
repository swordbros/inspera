<?php

namespace Swordbros\Base\Controllers;

use Illuminate\Support\Facades\DB;
use Site;
use Swordbros\Base\models\MetaModel;
use Swordbros\Event\Controllers\Event;
use Swordbros\Event\Models\EventModel;
use Swordbros\Event\Models\EventZoneModel;

class Meta extends BaseController{
    public $implement = [
        \Backend\Behaviors\FormController::class
    ];
    public $formConfig = 'meta_fields.yaml';
    public function __construct($formConfig=null)
    {
        if(!empty($formConfig))$this->formConfig = $formConfig;
        parent::__construct();

    }
    public function getMetaForm($modelId){

        $modelClass = $this->getConfig('modelClass', null);
        $config = ['id'=>null,'site_id'=>null,'module'=>null,'meta_key'=>null,'meta_value'=>null];
        $model = new $modelClass($config, 'swordbros_event_metas');
        $site_id = Site::getSiteIdFromContext();
        $records = $model->where([['owner_id', '=', $modelId], ['site_id', '=', $site_id]])->get();
        if(!$records->isEmpty()){
            foreach($records as $record){
                $model->attributes[$record->meta_key] = $record->meta_value;
            }
        }
        $this->asExtension('FormController')->initForm($model, 'update');
        return $this->formRender();
    }
}

