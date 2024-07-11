<?php
Route::get('swordbros/api/search', function() {
    return \Swordbros\Booking\Controllers\Api::search();
});
