<?php namespace Swordbros\Event;


use Site;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Base\TwigExtensions\BaseTwigExtension;
use System\Classes\PluginBase;

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
            \Swordbros\Event\Components\EventList::class => 'eventList',
            \Swordbros\Event\Components\EventDetail::class => 'eventDetail',
        ];
    }
    public function registerMailTemplates()
    {
        $site_id = Site::getSiteIdFromContext();
        $templates['swordbros.booking_request_new-'.$site_id] = 'swordbros.event::mail.booking_request_new';
        return $templates;
    }
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'event_url' => [Amele::class, 'event_url'],
                'localize_url' => [Amele::class, 'localize_url']
            ]
        ];
    }
    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
    public function registerPageSnippets(){
        return [
            \Swordbros\Event\Snippets\CinemaDescription::class => 'eventDescription',
        ];
    }
}
