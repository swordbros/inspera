<?php

namespace Swordbros\Auth\Controllers;

use Auth;
// use Backend\Classes\Controller;
use Carbon\Carbon;
use Event;
use Flash;
use Hash;
use Laravel\Socialite\Facades\Socialite;
use RainLab\User\Models\Settings as UserSettings;
use RainLab\User\Models\User as UserModel;
use Redirect;
use Request;
use Session;
use Throwable;

/**
 * Class LoginController
 *
 * @package Swordbros\Auth\Controllers
 */
class LoginController
{
    /**
     * @var array
     */
    private $providers = [
        'google',
        // 'apple',
        'facebook',
    ];

    /**
     * When user use login.
     *
     * @var string
     */
    const AUTH_LOGIN = 'login';

    /**
     * When user use register.
     *
     * @var string
     */
    const AUTH_REGISTER = 'register';

    /**
     * Temporary
     *
     * @var string
     */
    const CMS_INTEND_SESSION = 'url.cms.intended';

    /**
     * Temporary
     *
     * @var string
     */
    const REDIRECT_KEY = 'redirectTo';

    /**
     * Show Flash error messages
     *
     * @var bool
     */
    public $showFlashErrorMessage = true;

    /**
     * Success redirect to url address after login.
     *
     * @var string
     */
    public $successRedirectTo = '/';

    /**
     * Error redirect to url address if user do't found or some errors.
     *
     * @var string
     */
    public $errorRedirectTo = 'user/login';


    /**
     * @param \Illuminate\Support\Facades\Request $request
     * @param string $provider
     */
    public function social(Request $request, string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param \Illuminate\Support\Facades\Request $request
     * @param string $provider
     */
    public function login(Request $request, string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }
        $this->setReferer();

        return Socialite::driver($provider)
            ->redirect();
    }

    /**
     * @param \Illuminate\Support\Facades\Request $request
     * @param string $provider
     */
    public function register(Request $request, string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }
        $this->setReferer();

        return Socialite::driver($provider)
            ->redirect();
    }

    /**
     * @param \Illuminate\Support\Facades\Request $request
     * @param string $provider
     */
    public function callbackProvider(Request $request, string $provider)
    {
        dd($provider, $this->providers);
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }

        $user = null;
        try {
            $social_key = $provider . '_id';
            $socialiteUser = Socialite::driver($provider)->user();
            $socialUser = $socialiteUser->user;

            if (empty($socialUser)) {
                // No social user
                dd(['no user? error?']);
            }

            if ($user = $this->findUserBySocial($social_key, $socialUser['id'])) {
                // User exists and uses google/facebook
                // do nothing (login happens below)

            } elseif ($socialUser['email'] && $user = $this->findUserByEmail($socialUser['email'])) {
                // User exists but doesn't use google/facebook
                // Add the social_id to that user

                $user->update([
                    $social_key => $socialUser['id'],
                ]);
            } else {
                // New user, create with data from social provider
                // should redirect to another screen to complete profile?

                // Maybe user didn't allowed email scope?
                if (empty($socialUser['email'])) {
                    throw new \Exception('Email is required');
                }

                // temporary password for new user
                $password = Hash::make(uniqid());
                $user = Auth::register(
                    [
                        'name' => $socialUser['given_name'] ?? $socialUser['email'],
                        'surname' => $socialUser['family_name'] ?? $socialUser['email'],
                        'email' => $socialUser['email'],
                        'nickname' => $socialUser['email'],
                        'password' => $password,
                        'password_confirmation' => $password,
                        $provider . '_id' => $socialUser['id'],
                        'terms' => 1,
                        'phone' => '0',
                        // 'github_token' => $socialiteUser['token'],
                        // 'github_refresh_token' => $socialiteUser['refreshToken'],
                    ],
                    true, /* activate user */
                    false /* do not login */
                );

                $user->phone = null;
                $user->forceSave();
            }
        } catch (\Throwable $e) {
            $this->flashError($e->getMessage());

            return redirect()->to($this->errorRedirectTo);
        }

        if (!empty($user)) {
            Auth::login($user);
            $redirectTo = Session::get(static::REDIRECT_KEY, '/');

            return Redirect::intended($redirectTo);
        } else {
            $this->flashError('Failed to find user');

            return redirect()->to($this->errorRedirectTo);
        }
    }

    /**
     * @param string $message
     *
     * @return void
     */
    private function flashError(string $message)
    {
        if (false === $this->showFlashErrorMessage) {
            return;
        }

        Flash::error($message);
    }

    /**
     * @param string $email
     *
     * @return null|\RainLab\User\Models\User
     */
    private function findUserByEmail(string $email): ?UserModel
    {
        return UserModel::findByEmail($email);
    }

    /**
     * @param string $social_key
     * @param \Laravel\Socialite\Two\User $socialite
     *
     * @return null|\RainLab\User\Models\User
     */
    private function findUserBySocial(string $social_key, mixed $socialId): ?UserModel
    {
        return UserModel::where($social_key, $socialId)->first();
    }

    /**
     * Returns the login remember mode.
     *
     * @return null|string
     */
    public function rememberLoginMode(): ?string
    {
        return UserSettings::get('remember_login', UserSettings::REMEMBER_ALWAYS);
    }

    /**
     * useRememberLogin returns true if
     * persistent authentication should be used.
     *
     * @return bool
     */
    protected function useRememberLogin(): bool
    {
        switch ($this->rememberLoginMode()) {
            case UserSettings::REMEMBER_ALWAYS:
                return true;

            case UserSettings::REMEMBER_NEVER:
                return false;

            case UserSettings::REMEMBER_ASK:
                return (bool) post('remember', false);
        }
    }

    protected function setReferer()
    {
        // Rainlab.User has a intend() bug where the setIntendUrl and getIntendUrl are using the 'url.intended' key,
        // but the intend() redirection is using `url.cms.intended`
        Session::put(static::CMS_INTEND_SESSION, request()->headers->get('referer', '/'));

        // Must save this in case it's an User registration (user must update the phone), the 'intended' url is lost,
        // so we save a persistent one to be retrieved on plugins/capsule/auth/classes/event/ExtendUserRedirectHandler.php
        Session::put(static::REDIRECT_KEY, request()->headers->get('referer', '/'));
    }
}
