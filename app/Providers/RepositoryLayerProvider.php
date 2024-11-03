<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IItemRepository;
use App\Repositories\Interfaces\IListRepository;
use App\Repositories\ItemRepository;
use App\Repositories\ListRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IListRepository::class, ListRepository::class);
        $this->app->bind(IItemRepository::class, ItemRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
