<?php
Route::get('swordbros/api/search', function() {
    return \Swordbros\Event\Controllers\Api::search();
});
