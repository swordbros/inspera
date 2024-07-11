<?php

namespace Swordbros\ThemeExtender\Classes;

use Backend\Facades\BackendAuth;
use Event;
use October\Rain\Router\Helper;
use OFFLINE\Boxes\Classes\CMS\Controller;
use OFFLINE\Boxes\Models\Page;

class ExtendBoxesPagesWithSlug
{

    public function subscribe(): void
    {
        Page::extend(function ($page) {
            // Overwrite the $rules property for url to allow slugs
            $page->bindEvent('model.beforeValidate', function () use ($page) {
                $page->rules['url'] = ['nullable', 'regex:/^[a-z0-9\/_\-\.\:]*$/i'];
            });
        });

        Event::listen('cms.router.beforeRoute', function ($url, &$router) {
            $pages = Page::query()->where('url', 'LIKE', '%/:%')->get();
            foreach ($pages as $page) {
                $rootUrlParts = explode('/:', $page->url);
                if (str_starts_with($url, $rootUrlParts[0])) {
                    $urlSegments = Helper::segmentizeUrl($url);
                    $pageSegments = Helper::segmentizeUrl($page->url);
                    if (count($urlSegments) == count($pageSegments)) {
                        $params = [];
                        foreach ($pageSegments as $index => $pageSegment) {
                            if (str_starts_with($pageSegment, ':')) {
                                $params[substr($pageSegment, 1)] = $urlSegments[$index];
                            }
                        }
                        $router->setParameters($params);
                        if (str_contains($url, Controller::PREVIEW_URL) && BackendAuth::getUser()) {
                            return Controller::instance()->getPreviewPage($page->url);
                        }
                        return Controller::instance()->getCmsPageForUrl($page->url);
                    }
                }
            }
        }, 200);
    }
}
