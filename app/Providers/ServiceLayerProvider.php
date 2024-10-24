<?php

namespace App\Providers;

use App\Services\CategoryService;
use App\Services\Interfaces\ICategoryService;
use App\Services\Interfaces\IListService;
use App\Services\ListService;
use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ICategoryService::class, CategoryService::class);
        $this->app->bind(IListService::class, ListService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
