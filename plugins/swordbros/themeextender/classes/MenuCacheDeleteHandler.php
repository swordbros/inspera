<?php

namespace Swordbros\ThemeExtender\Classes;

use Cache;
use Event;
use Swordbros\ThemeExtender\Components\Menu;
use RainLab\Pages\Classes\Menu as RainLabMenu;
use OFFLINE\Boxes\Models\Page;
use Cms\Classes\Theme;

class MenuCacheDeleteHandler
{
    public function subscribe(): void
    {
        /**
         * Clear cached version of static menu on its update
         */
        Event::listen('pages.object.save', function ($event, $object, $type) {
            if ($type == "menu") {
                $prefix = Menu::CACHE_PREFIX;
                Cache::forget($prefix . $object->code);
            }
        });

        /**
         * Clear cached version of all static menus on update,
         * clear boxes simple menu
         */
        Page::extend(function ($page) {
            $page->bindEvent('model.afterSave', function () use ($page) {
                Cache::forget(Menu::CACHE_PREFIX . Menu::ALL_PAGES_MENU_CODE);

                RainLabMenu::listInTheme(Theme::getActiveTheme(), true)->each(function ($item) {
                    Cache::forget(Menu::CACHE_PREFIX . $item->code);
                });

                $page->afterSave();
            });
        });
    }
}
