<?php namespace Swordbros\Event\Components;

use Media\Classes\MediaLibrary;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Event\Models\EventModel;
use System;
use Backend;
use Cms\Classes\ComponentBase;

/**
 * BackendLink component
 */
class EventList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Swordbros Events List',
            'description' => 'Show Swordbros Events.'
        ];
    }

    public function onRun()
    {
        $this->page['mediaUrl'] = MediaLibrary::url('/');
        $this->page['title'] = __('swordbros.event::lang.plugin.events');
        $this->page['events'] = EventModel::where(['status'=>1])->get();
    }
}
