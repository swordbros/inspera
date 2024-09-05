<?php namespace Swordbros\Booking\Components;

use Media\Classes\MediaLibrary;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Booking\models\BookingRequestModel;
use System;
use Backend;
use Cms\Classes\ComponentBase;

/**
 * BackendLink component
 */
class BookingRequestDetail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Booking Request Detail',
            'description' => 'Show a Booking Request Detail.'
        ];
    }

    public function onRun()
    {
        $id = $this->param('id');
        $this->page['title'] = __('plugin.booking_request_detail');
        $this->page['bookingRequest'] = BookingRequestModel::find($id);
        $this->page['bookingRequest']->start_fdate = Amele::humanDate($this->page['bookingRequest']->event->start, 'd.m.Y');
        $this->page['bookingRequest']->start_fhour = Amele::humanDate($this->page['bookingRequest']->event->start, 'H:i');
    }
}
