<?php namespace Swordbros\Booking\Components;

use Media\Classes\MediaLibrary;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Booking\Models\BookingModel;
use System;
use Backend;
use Cms\Classes\ComponentBase;

/**
 * BackendLink component
 */
class Booking extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Last Bookings',
            'description' => 'Show Last Bookings.'
        ];
    }

    public function onRun()
    {
        $this->page['mediaUrl'] = MediaLibrary::url('/');
        $this->page['title'] = __('plugin.bookings');
        $this->page['bookings'] = BookingModel::all();
    }
}
