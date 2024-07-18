<?php namespace Swordbros\Setting;


use Site;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Base\TwigExtensions\BaseTwigExtension;
use Swordbros\Setting\Models\SwordbrosSettingModel;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

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
    }
    public function registerMailTemplates()
    {
    }
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'swordbros_setting' => [SwordbrosSettingModel::class, 'swordbros_setting'],
            ]
        ];
    }
    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Booking Store Settings',
                'description' => 'Manage Booking Store settings.',
                'category' => SettingsManager::CATEGORY_CMS,
                'icon'        => 'icon-university',
                'class'       => '\Swordbros\Setting\Models\SwordbrosSettingModel',
                'order'       => 001,
                'keywords'    => 'booking store settings'
            ]
        ];
    }
    public function registerPageSnippets(){
    }

}
