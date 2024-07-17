<?php
Route::get('swordbros/api/request-approve', function() {
    return \Swordbros\Booking\Controllers\Api::requestApprove();
})->name('request-approve');
Route::get('swordbros/api/request-decline', function() {
    return \Swordbros\Booking\Controllers\Api::requestDecline();
})->name('request-decline');
