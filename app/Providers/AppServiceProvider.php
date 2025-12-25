<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Add this line to import the URL facade
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
