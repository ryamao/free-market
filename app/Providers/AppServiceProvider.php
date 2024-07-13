<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (App::environment('production')) {
            URL::forceScheme('https');
            app('request')->server->set('HTTPS', true);
        }

        $this->app->singleton(
            \Stripe\StripeClient::class,
            function () {
                return new \Stripe\StripeClient([
                    'api_key' => config('cashier.secret'),
                ]);
            },
        );
    }
}
