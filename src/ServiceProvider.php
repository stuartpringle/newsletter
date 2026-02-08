<?php

namespace StuartPringle\Newsletter;

use Illuminate\Support\Facades\View;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use StuartPringle\Newsletter\Models\MailingListSignup;

class ServiceProvider extends AddonServiceProvider
{
    protected $routeNamespace = 'StuartPringle\\Newsletter\\Http\\Controllers';

    protected $viewNamespace = 'newsletter';

    protected $routes = [
        'web' => __DIR__.'/../routes/web.php',
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    public function register(): void
    {
        parent::register();

        // Ensure views are available even if the Statamic addon manifest isn't built yet.
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'newsletter');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/newsletter.php', 'newsletter');
    }

    public function bootAddon(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/newsletter.php' => config_path('newsletter.php'),
            ], 'newsletter-config');
        }

        View::composer('newsletter::widgets.mailing_list_signups', function ($view) {
            $signups = MailingListSignup::latest()->limit(10)->get();
            $view->with('signups', $signups);
        });

        Nav::extend(function ($nav) {
            $nav->create('Subscribers')
                ->section('Newsletter')
                ->route('newsletter.index')
                ->icon('email-utility');

            $nav->create('Lists')
                ->section('Newsletter')
                ->route('newsletter.lists.index')
                ->icon('list');

            $nav->create('Tags')
                ->section('Newsletter')
                ->route('newsletter.tags.index')
                ->icon('tag');

            $nav->create('Segments')
                ->section('Newsletter')
                ->route('newsletter.segments.index')
                ->icon('filter');

            $nav->create('Newsletter Users')
                ->section('Newsletter')
                ->route('newsletter.users.index')
                ->icon('users');

            $nav->create('Templates')
                ->section('Newsletter')
                ->route('newsletter.templates.index')
                ->icon('template');

            $nav->create('Campaigns')
                ->section('Newsletter')
                ->route('newsletter.campaigns.index')
                ->icon('paper-plane');
        });
    }
}
