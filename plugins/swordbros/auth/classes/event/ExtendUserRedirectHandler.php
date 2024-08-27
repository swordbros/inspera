<?php

namespace Swordbros\Auth\Classes\Event;

use Flash;
use RainLab\User\Models\User;
use Session;
use Swordbros\Auth\Controllers\LoginController;

/**
 * Class ExtendUserRedirectHandler.
 *
 * @package Swordbros\Auth\Classes\Event
 */
class ExtendUserRedirectHandler
{
    const CMS_INTEND_SESSION = 'url.cms.intended';
    const USER_SETTINGS_URL = '/user/account-settings';

    /**
     * Add listeners to User Login Event.
     *
     * @param \Illuminate\Events\Dispatcher $event
     */
    public function subscribe($events)
    {
        $events->listen('rainlab.user.login', [$this, 'onLogin']);
        $events->listen('rainlab.user.update', [$this, 'onUpdate']);
    }

    /**
     * @param User $user
     */
    public function onLogin($user = null)
    {
        // User is logged in and has no phone number
        if ($user && empty($user->phone)) {
            Flash::warning(trans('swordbros.themeextender::lang.user.validation.phone'));

            // Uses 'redirect' post input. Done in the Rainlab.User `onSignin` method
            if (request()->ajax()) {
                request()->merge(['redirect' => static::USER_SETTINGS_URL]);
            } else {
                // Rainlab.User has a intend() bug where the setIntendUrl and getIntendUrl are using the 'url.intended' key,
                // but the intend() redirection is using `url.cms.intended`
                Session::put(static::CMS_INTEND_SESSION, static::USER_SETTINGS_URL);
            }
        } elseif (request()->ajax()) {
            // This makes the page refresh or redirect to $destination
            $destination = '/';

            try {
                $refererUrl = request()->headers->get('referer', '/');
                $queryString = parse_url($refererUrl, PHP_URL_QUERY);
                parse_str(parse_url($refererUrl, PHP_URL_QUERY), $queryStringArray);
                $destination = $queryStringArray['destination'];
            } catch (\Exception $e) {
                // do nothing
            }

            request()->merge(['redirect' => $destination]);
        }
    }

    public function onUpdate($user, $data)
    {
        $redirectTo = Session::get(LoginController::REDIRECT_KEY, '/');

        if (request()->ajax()) {
            request()->merge(['redirect' => $redirectTo]);
        } else {
            // Rainlab.User has a intend() bug where the setIntendUrl and getIntendUrl are using the 'url.intended' key,
            // but the intend() redirection is using `url.cms.intended`
            Session::put(static::CMS_INTEND_SESSION, $redirectTo);
        }
        Session::forget(LoginController::REDIRECT_KEY);
    }
}
