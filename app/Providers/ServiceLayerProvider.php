<?php

namespace App\Providers;

use App\Services\CategoryService;
use App\Services\Interfaces\ICategoryService;
use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ICategoryService::class, CategoryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
