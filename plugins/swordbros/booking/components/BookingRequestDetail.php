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
        $otp = $this->param('otp');
        $this->page['title'] = __('plugin.booking_request_detail');
        $bookingRequest = BookingRequestModel::find($id);
        if($bookingRequest && $bookingRequest->otp == $otp){
            $this->page['bookingRequest'] = $bookingRequest;
            $this->page['bookingRequest']->start_fdate = Amele::humanDate($this->page['bookingRequest']->event->start, 'd.m.Y');
            $this->page['bookingRequest']->start_fhour = Amele::humanDate($this->page['bookingRequest']->event->start, 'H:i');
        } else {
            $this->page['bookingRequest'] = null;
        }
    }
}
