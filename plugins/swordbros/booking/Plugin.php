<?php namespace Swordbros\Booking;

use Backend;
use Swordbros\Booking\Controllers\BookingRequest;
use Swordbros\Booking\Models\BookingModel;
use Swordbros\Booking\models\BookingRequestModel;
use System\Classes\PluginBase;
use Yaml;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            \Swordbros\Booking\Components\Booking::class => 'swordbrosBooking',
            \Swordbros\Booking\Components\BookingDetail::class => 'swordbrosBookingDetail',
            \Swordbros\Booking\Components\BookingRequestDetail::class => 'swordbrosBookingRequestDetail',
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
    public function registerNavigation()
    {
        $bookingCount = BookingModel::where(['is_new'=> 1])->count();
        $requestCount = BookingRequestModel::where(['is_new'=> 1])->count();
        $navigation = [
            'main-menu-item' => [
                'label'       => 'Booking',
                'url'         => Backend::url('swordbros/booking/bookingrequest'),
                'icon'        => 'icon-tags',
                'order'=> 402,
                'counter'      => ($bookingCount + $requestCount),
                'counterLabel' => trans('New Booking Requests and Bookings'),
                'sideMenu' => [
                    'side-menu-item' => [
                        'label' => 'Calendar',
                        'url' => Backend::url('swordbros/booking/bookingcalendar'),
                        'icon' => 'icon-calendar',
                    ],
                    'side-menu-item2' => [
                        'label' => 'Requests',
                        'url' => Backend::url('swordbros/booking/bookingrequest'),
                        'icon' => 'icon-book',
                        'counter'      => ( $requestCount),
                        'counterLabel' => trans('New Booking Requests'),
                    ],
                    'side-menu-item3' => [
                        'label' => 'Bookings',
                        'url' => Backend::url('swordbros/booking/booking'),
                        'icon' => 'icon-puzzle-piece',
                        'counter'      => ( $bookingCount),
                        'counterLabel' => trans('New Bookings'),
                    ],
                    'side-menu-item4' => [
                        'label' => 'Create Booking',
                        'url' => Backend::url('swordbros/booking/booking/create'),
                        'icon' => 'icon-tag',
                    ],
                ],
            ],
        ];
        return $navigation;
    }
}
