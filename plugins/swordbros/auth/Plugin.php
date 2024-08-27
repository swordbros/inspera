<?php

namespace Swordbros\Auth;

use App;
use Backend;
use Swordbros\Auth\Classes\Event\ExtendUserRedirectHandler;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Middleware\TrustProxies;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\ServiceProvider;
use System\Classes\PluginBase;
use SocialiteProviders\Manager\SocialiteWasCalled;

/**
 * Auth Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Swordbros Auth',
            'description' => 'Swordbros Auth',
            'author' => 'Swordbros',
            'icon' => 'icon-leaf',
        ];
    }

    public function register()
    {
        /**
         * Register service providers
         */
        App::register(ServiceProvider::class);
        AliasLoader::getInstance()->alias('Socialite', Socialite::class);
    }

    public function boot()
    {
        $this->addTrustedProxyMiddleware();
        $this->listenSocialCalls();
        $this->listenToLoginEventsToCompleteProfile();
    }

    /**
     * Listens to Auth::login events to ask user to complete profile
     */
    public function listenToLoginEventsToCompleteProfile()
    {
        Event::subscribe(ExtendUserRedirectHandler::class);
    }

    /**
     * The Fideloper\Proxy must be manually added to the Middleware stack
     */
    private function addTrustedProxyMiddleware()
    {
        $this->app['Illuminate\Contracts\Http\Kernel']
            ->prependMiddleware(TrustProxies::class);
    }

    public function listenSocialCalls()
    {
        Event::listen(SocialiteWasCalled::SERVICE_CONTAINER_PREFIX . 'google', \SocialiteProviders\Google\GoogleExtendSocialite::class . '@handle');
        // Event::listen(\SocialiteProviders\Manager\SocialiteWasCalled::class, \SocialiteProviders\Facebook\FacebookExtendSocialite::class.'@handle');
    }
}
