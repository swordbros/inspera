<?php

namespace Swordbros\ThemeExtender\Components;

use Cache;
use Closure;
use Cms\Classes\ComponentBase;
use Cms\Classes\Theme;
use Illuminate\Support\Facades\URL;
use RainLab\Pages\Classes\Menu as PagesMenu;
use OFFLINE\Boxes\Models\Page;

class Menu extends ComponentBase
{
    const CACHE_PREFIX = 'menu.';
    const ALL_PAGES_MENU_CODE = 'boxesPages';
    public $menus;
    public $theme;
    private $seconds;

    public function componentDetails()
    {
        return [
            'name' => 'Menu Component',
            'description' => 'Handles and caches site menus',
        ];
    }

    public function defineProperties()
    {
        return [
            'codes' => [
                'title' => 'Use selected Static Menus',
                'description' => '',
                'type' => 'set',
            ],
            'useBoxesTree' => [
                'title' => 'Output all pages',
                'description' => 'Output not hidden boxes pages',
                'type' => 'checkbox',
            ],
            'cacheSeconds' => [
                'title' => 'Seconds to refresh menu cache',
                'default' => 2,
            ],
        ];
    }

    public function init()
    {
        $this->theme = Theme::getActiveTheme();
        $this->seconds = $this->property('cacheSeconds');
    }

    public function onRun()
    {
        if ($this->property('codes')) {
            $this->page['menus'] = $this->menus = $this->getStaticMenus();
        }

        if ($this->property('useBoxesTree')) {
            $this->page['menus'] = $this->menus = $this->getBoxesTree();
        }
    }

    public function getCodesOptions(): array
    {
        $codes = PagesMenu::listInTheme(Theme::getActiveTheme(), true)
            ->lists('name', 'code');
        return $codes;
    }

    public static function iterateChildMenuItems($currentUrl, ?bool $allowNesting = true): Closure
    {
        $iterator = function (\October\Rain\Database\Collection $children) use (
            &$iterator,
            $currentUrl,
            $allowNesting
        ) {
            $branch = [];

            foreach ($children as $child) {
                $pageUrl = URL::to($child->url);
                $item = [
                    'url' => $pageUrl,
                    'title' => $child->name,
                    'isActive' => $pageUrl === $currentUrl,
                    'viewBag' => ['isHidden' => $child->is_hidden || $child->is_hidden_in_navigation],
                ];

                if ($allowNesting && $child->children->count() > 0) {
                    $item['items'] = $iterator($child->children);
                    $item['isChildActive'] = !!count(array_filter($item['items'], fn ($sub) => $sub['isActive']));
                }

                $branch[] = $item;
            }

            return $branch;
        };

        // Make sure php-cs-fixer does not strip out the $iterator variable.
        // It is used as a reference in the closure above.
        $_ = $iterator;

        return $iterator;
    }

    /**
     * Get all boxes pages for menu (exclude isHidden),
     * use as {{ menus.boxesPages }}
     *
     * @return array|null
     */
    public function getBoxesTree(): ?array
    {
        $seconds = $this->seconds;
        return Cache::remember(self::CACHE_PREFIX . self::ALL_PAGES_MENU_CODE, $seconds, function () {
            $iterator = self::iterateChildMenuItems(url($this->page->page['url']), true);

            $query = Page::query()
                ->current()
                ->where('url', '<>', '')
                ->where('is_hidden', false)
                ->where('is_hidden_in_navigation', false)
                ->whereRaw('JSON_EXTRACT(custom_config, "$.hide_in_main_nav") = "0"');

            $allPages = $query->get()->toNested(false);
            return $iterator($allPages);
        });
    }

    /**
     * Load all static menus, key by menu code: dashed to camelCase,
     * use with component name {{ menu.menus.newMenu }} or page var {{ menus.main }}
     */
    public function getStaticMenus(): array
    {
        $menus = [];
        $codes = $this->property('codes');

        foreach ($codes as $code) {
            $codeKey = $this->dashedToCamelCase($code);
            $menus[$codeKey] = $this->getMenu($code);
        }

        return $menus;
    }

    /**
     * Loads cached menu by code
     */
    public function getMenu(string $menuCode): array
    {
        $seconds = $this->seconds;
        $page = $this->page;

        $menuItems = Cache::remember(self::CACHE_PREFIX . $menuCode, $seconds, function () use ($menuCode, $page) {
            $menu = PagesMenu::loadCached($this->theme, $menuCode);

            $items = $this->filterVisibleItems($menu->generateReferences($page));

            return $items;
        });

        return $menuItems;
    }

    private function dashedToCamelCase(string $input): string
    {
        $words = array_map('ucfirst', explode('-', $input));

        return lcfirst(implode('', $words));
    }

    private function filterVisibleItems(array $items, $onlyMain = false): array
    {
        $result = [];

        foreach ($items as $key => $item) {
            if (is_array($item)) {
                $filteredValue = $this->filterVisibleItems($item, $onlyMain);
                if (!empty($filteredValue)) {
                    $result[$key] = $filteredValue;
                }
            } else {
                $isHidden = (isset($item->viewBag['isHidden']))
                    && (($item->viewBag['isHidden'] === true) || ((int)$item->viewBag['isHidden'] === 1));
                if (!$isHidden) {
                    $result[$key] = $item;
                }
            }
        }

        return $result;
    }
}
