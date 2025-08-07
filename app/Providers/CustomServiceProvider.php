<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TemplateService;
use App\Services\EventBuilderService;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TemplateService::class, function ($app) {
            return new TemplateService();
        });

        $this->app->singleton(EventBuilderService::class, function ($app) {
            return new EventBuilderService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}