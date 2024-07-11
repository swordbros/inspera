<?php

namespace Swordbros\ThemeExtender\Components;

use Cms\Classes\ComponentBase;
use OFFLINE\Boxes\Models\Page;

class Breadcrumb extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'Breadcrumb for Offline Boxes',
            'description' => 'Add breadcrumb to offline boxes pages'
        ];
    }

    public function onRender()
    {
        $currentPage = Page::where('url', $this->page->page->url)->first();
        if (!$currentPage) return;
        $items = [$currentPage->name];
        if ($parent = $currentPage->parent) {
            $url = $parent->url;
            $name = $parent->name;
            $items[$url] = $name;
        }
        $this->page['breadcrumb_items'] = array_reverse($items);
    }
}
