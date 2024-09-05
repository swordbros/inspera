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
class BookingDetail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Booking Detail',
            'description' => 'Show a Booking Detail.'
        ];
    }

    public function onRun()
    {
        $this->page['title'] = __('plugin.booking_detail');
        $this->page['booking'] = BookingModel::first();
    }
}
