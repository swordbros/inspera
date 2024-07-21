<?php

use Swordbros\Setting\Models\SwordbrosSettingModel;

Route::get('swordbros/api/search', function() {
    return \Swordbros\Event\Controllers\Api::search();
});
function swordbross_setting($setting_key){
    return SwordbrosSettingModel::swordbros_setting($setting_key);
}
