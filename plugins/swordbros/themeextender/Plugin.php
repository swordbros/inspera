<?php

namespace Swordbros\ThemeExtender;

use App;
use Backend\Models\BrandSetting;
use Config;
use Event;
use Swordbros\ThemeExtender\Components\Breadcrumb;
use Swordbros\ThemeExtender\Classes\AddEssentialsVars;
use Swordbros\ThemeExtender\Components\Menu;
use Swordbros\ThemeExtender\Classes\ExtendBoxesPage;
use Swordbros\ThemeExtender\Classes\ExtendBoxesPagesWithSlug;
use Swordbros\ThemeExtender\Classes\ExtendBoxesScaffoldingClasses;
use Swordbros\ThemeExtender\Classes\MenuCacheDeleteHandler;
use System\Classes\PluginBase;
use System\Helpers\View;

class Plugin extends PluginBase
{
    public $require = ['RainLab.Pages', 'OFFLINE.Boxes'];

    public function pluginDetails()
    {
        return [
            'name' => 'ThemeExtender',
            'description' => 'This plugin extends the theme for our needs e.g. boxes extension',
            'author' => 'Swordbros',
            'icon' => 'icon-leaf'
        ];
    }

    public function register()
    {
        config(['offline.boxes::config.render_scaffolding' => false]);
    }

    public function boot()
    {
        Event::subscribe(ExtendBoxesPagesWithSlug::class);
        Event::subscribe(ExtendBoxesPage::class);
        Event::subscribe(MenuCacheDeleteHandler::class);
        Event::subscribe(ExtendBoxesScaffoldingClasses::class);
        Event::subscribe(AddEssentialsVars::class);

        /*        Event::listen('backend.menu.extendItems', function(NavigationManager $navigationManager) {
            $user = \BackendAuth::getUser(); // get the logged in user
           $navigationManager->removeMainMenuItem('Rainlab.Pages', 'pages');
            $navigationManager->addMainMenuItem('Rainlab.Pages', 'pages',
            [
                'label'       => 'rainlab.pages::lang.plugin.name',
                'url'         => Backend::url('rainlab/pages#menu'),
                'icon'        => 'icon-files-o',
                'attributes'  => ['data-menu-item'=>'menus'],
                'iconSvg'     => 'plugins/rainlab/pages/assets/images/pages-icon.svg',
                'permissions' => ['rainlab.pages.*'],
                'order'       => 200,
                'useDropdown' => false,
            ]);
        });*/
        /**
         * Adds custom classes only to editor in backend
         */
        // \Event::listen(
        //     \OFFLINE\Boxes\Classes\Events::EDITOR_RENDER,
        //     function (\OFFLINE\Boxes\Components\BoxesPageEditor $editor) {
        //         $editor->addCss('/themes/thetheme/assets/styles/box-editor.css');
        //     }
        // );

    }

    public function registerComponents()
    {
        return [
            Menu::class => 'boxesMenu',
            Breadcrumb::class => 'breadCrumb',
        ];
    }
}
