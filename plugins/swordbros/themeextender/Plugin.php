<?php

namespace Swordbros\ThemeExtender;

use Event;
use Swordbros\Event\Components\EventList;
use Swordbros\ThemeExtender\Components\Breadcrumb;
use Swordbros\ThemeExtender\Classes\AddEssentialsVars;
use Swordbros\ThemeExtender\Classes\ExtendBoxesPage;
use Swordbros\ThemeExtender\Classes\ExtendBoxesPagesWithSlug;
use Swordbros\ThemeExtender\Classes\ExtendBoxesScaffoldingClasses;
use Swordbros\ThemeExtender\Classes\ExtendUserModel;
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

        Event::subscribe(ExtendUserModel::class);

        Event::listen(
            \OFFLINE\Boxes\Classes\Events::EDITOR_RENDER,
            function (\OFFLINE\Boxes\Components\BoxesPageEditor $editor) {
                $editor->addCss('/themes/inspera/assets/css/box-editor.css');
            }
        );

        \OFFLINE\Boxes\Models\Box::extend(function ($box) {
            $box->belongsTo = [
                'post_1' => [
                    \Tailor\Models\EntryRecord::class,
                    'blueprint' => 'edcd102e-0525-4e4d-b07e-633ae6c18db6',
                    'replicate' => false
                ],
                'post_2' => [
                    \Tailor\Models\EntryRecord::class,
                    'blueprint' => 'edcd102e-0525-4e4d-b07e-633ae6c18db6',
                    'replicate' => false
                ],
                'post_3' => [
                    \Tailor\Models\EntryRecord::class,
                    'blueprint' => 'edcd102e-0525-4e4d-b07e-633ae6c18db6',
                    'replicate' => false
                ],
                'post_4' => [
                    \Tailor\Models\EntryRecord::class,
                    'blueprint' => 'edcd102e-0525-4e4d-b07e-633ae6c18db6',
                    'replicate' => false
                ]
            ];
            $box->initializeBlueprintRelationModel();

            $box->addDynamicMethod('getTagsOptions', function () {
                return EventList::getFeaturedTags();
            });

            $box->addDynamicMethod('getVenuesOptions', function () {
                return EventList::getVenues();
            });
        });
    }

    public function registerComponents()
    {
        return [
            Breadcrumb::class => 'breadCrumb',
        ];
    }
}
