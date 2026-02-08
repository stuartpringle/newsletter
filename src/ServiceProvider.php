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

    public function bootAddon(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        View::composer('newsletter::widgets.mailing_list_signups', function ($view) {
            $signups = MailingListSignup::latest()->limit(10)->get();
            $view->with('signups', $signups);
        });

        Nav::extend(function ($nav) {
            $nav->create('Subscribers')
                ->section('Newsletter')
                ->route('newsletter.index')
                ->icon('email-utility');
        });
    }
}
