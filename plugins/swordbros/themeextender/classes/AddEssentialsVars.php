<?php

namespace Swordbros\ThemeExtender\Classes;

use App;
use Config;
use Backend\Models\BrandSetting;
use Event;
use View;

class AddEssentialsVars
{

    public function subscribe(): void
    {
        App::before(function () {
            // Share the variables with the mail template system
            Event::listen('mailer.beforeAddContent', function () {
                $appVars = $this->getAppVars();
                foreach ($appVars as $key => $var) {
                    View::share($key, $var);
                }
            });

            // Share the variables with the CMS template system
            Event::listen('cms.page.beforeDisplay', function ($controller, $url, $page) {
                $appVars = $this->getAppVars();
                foreach ($appVars as $key => $var) {
                    $controller->vars[$key] = $var;
                }
            });
        });
    }

    protected function getAppVars(): array
    {
        return [
            'app_url' => url('/'),
            'app_logo' => BrandSetting::getLogo() ?: url('/modules/backend/assets/images/october-logo.svg'),
            'app_favicon' => BrandSetting::getFavicon() ?: url('/modules/backend/assets/images/favicon.png'),
            'app_name' => BrandSetting::get('app_name'),
            'app_debug' => Config::get('app.debug', false),
            'app_description' => BrandSetting::get('app_tagline')
        ];
    }
}
