<?php

namespace Swordbros\ThemeExtender;

use Event;
use Swordbros\ThemeExtender\Components\Breadcrumb;
use Swordbros\ThemeExtender\Classes\AddEssentialsVars;
use Swordbros\ThemeExtender\Classes\ExtendBoxesPage;
use Swordbros\ThemeExtender\Classes\ExtendBoxesPagesWithSlug;
use Swordbros\ThemeExtender\Classes\ExtendBoxesScaffoldingClasses;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = ['OFFLINE.Boxes'];

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
        Event::subscribe(ExtendBoxesScaffoldingClasses::class);
        Event::subscribe(AddEssentialsVars::class);

        Event::listen(
            \OFFLINE\Boxes\Classes\Events::EDITOR_RENDER,
            function (\OFFLINE\Boxes\Components\BoxesPageEditor $editor) {
                $editor->addCss('/themes/inspera/assets/css/box-editor.css');
            }
        );
    }

    public function registerComponents()
    {
        return [
            Breadcrumb::class => 'breadCrumb',
        ];
    }
}
