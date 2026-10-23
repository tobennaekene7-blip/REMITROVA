<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Belt and braces alongside trustProxies(): if APP_URL is https,
        // every generated URL (including the webhook callbackUrl sent to
        // Paga) is https regardless of how the request reached the app.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
