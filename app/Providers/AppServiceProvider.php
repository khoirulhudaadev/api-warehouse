<?php

namespace App\Providers;

use App\Repositories\DeliveryRepository;
use App\Repositories\DeliveryRepositoryInterface;
use App\Repositories\ItemRepository;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\TypeRepository;
use App\Repositories\TypeRepositoryInterface;
use App\Repositories\UnitRepository;
use App\Repositories\UnitRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TypeRepositoryInterface::class, TypeRepository::class);
        $this->app->bind(UnitRepositoryInterface::class, UnitRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(DeliveryRepositoryInterface::class, DeliveryRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
