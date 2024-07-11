<?php

namespace Swordbros\ThemeExtender\Classes;

use ApplicationException;
use Event;
use Lang;
use OFFLINE\Boxes\Models\Page;
use OFFLINE\Boxes\Controllers\EditorController;
use Cms\Classes\Layout;
use Cms\Classes\Theme;
use October\Rain\Filesystem\Definitions as FileDefinitions;

class ExtendBoxesPage
{

    public function subscribe(): void
    {

        Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof EditorController) {
                return;
            }

            if (!$widget->model instanceof Page) {
                return;
            }

            // Add your custom form fields to the Page form.
            // Make sure to use the `custom_config` field name.
            //
            // This field will be added to the Editor -> Page form
            // in the administration area.
            $widget->addFields([
                // 'layout' => [
                //     'label' => 'cms::lang.editor.layout',
                //     'type' => 'dropdown',
                //     'tab' => 'CMS',
                //     'options' => 'getBoxesLayoutOptions'
                // ],
                'is_hidden' => [
                    'label' => 'cms::lang.editor.hidden',
                    'comment' => 'cms::lang.editor.hidden_comment',
                    'type' => 'checkbox',
                    'tab' => 'Swordbros.themeextender::lang.boxes.page.visibility',
                ],
                'is_hidden_in_navigation' => [
                    'label' => 'offline.boxes::lang.is_hidden_in_navigation',
                    'comment' => 'offline.boxes::lang.is_hidden_in_navigation_comment',
                    'type' => 'checkbox',
                    'tab' => 'Swordbros.themeextender::lang.boxes.page.visibility',
                ],



                /*        is_hidden_in_navigation:
            label: 'offline.boxes::lang.is_hidden_in_navigation'
            comment: 'offline.boxes::lang.is_hidden_in_navigation_comment'
            type: checkbox
            tab: CMS*/

                'custom_config[logo_color]' => [
                    'label' => 'Swordbros.themeextender::lang.boxes.page.logo_color_label',
                    'type' => 'balloon-selector',
                    'default' => 'white',
                    'tab' => 'CMS',
                    'span' => 'auto',
                    'options' => [
                        'white' => 'Swordbros.themeextender::lang.boxes.page.logo_color_white',
                        'black' => 'Swordbros.themeextender::lang.boxes.page.logo_color_black'
                    ]
                ],
                'custom_config[hide_in_main_nav]' => [
                    'label' => 'Swordbros.themeextender::lang.boxes.page.hide_in_main_label',
                    'type' => 'checkbox',
                    'default' => 'true',
                    'tab' => 'Swordbros.themeextender::lang.boxes.page.visibility',
                ],
                'custom_config[show_breadcrumb]' => [
                    'label' => 'Swordbros.themeextender::lang.boxes.page.show_breadcrumb',
                    'type' => 'checkbox',
                    'order' => 30,
                    'default' => true,
                    'tab' => 'CMS',
                ],
                'slug' => [
                    'label' => 'offline.boxes::lang.instance_slug',
                    'tab' => 'offline.boxes::lang.dev_tab',
                    'order' => 200,
                ],
            ], 'primary');
        });

        Page::extend(function ($model) {
            $model->addDynamicMethod('getBoxesLayoutOptions', function () {
                if (!($theme = Theme::getEditTheme())) {
                    throw new ApplicationException(Lang::get('cms::lang.theme.edit.not_found'));
                }

                $result = [];
                $defaultLayout = '';
                $layouts = Layout::listInTheme($theme, true);

                foreach ($layouts as $layout) {
                    if (!$layout->boxes_layout) {
                        continue;
                    }

                    $baseName = $layout->getBaseFileName();
                    $name = strlen($layout->name) ? $layout->name : $baseName;
                    if ($layout->boxes_default_layout) {
                        $defaultLayout = $name;
                    }

                    if (FileDefinitions::isPathIgnored($baseName)) {
                        continue;
                    }

                    $result[$baseName] = $name;
                }

                if ($defaultLayout) {
                    unset($result[$defaultLayout]);
                    $result = [$defaultLayout => $defaultLayout] + $result;
                } else {
                    $result = [null => $defaultLayout ?: Lang::get('cms::lang.page.no_layout')] + $result;
                }

                return $result;
            });
        });
    }
}
