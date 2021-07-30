<?php

namespace App\Providers;

use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\Interfaces\CartItemRepositoryInterface;
use App\Repository\Interfaces\CartRepositoryInterface;
use App\Repository\Interfaces\CategoryRepositoryInterface;
use App\Repository\Interfaces\OrderTransactProductRepositoryInterface;
use App\Repository\Interfaces\OrderTransactRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repository\ProductRepository;
use App\Repository\Interfaces\ProductRepositoryInterface;
use App\Repository\Interfaces\ProgramSettingRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\NonLocalRepository\UserRepository;
use App\Repository\OrderTransactProductRepository;
use App\Repository\OrderTransactRepository;
use App\Repository\ProgramSettingRepository;
use App\Repository\Interfaces\BusinessNatureRepositoryInterface;
use App\Repository\BusinessNatureRepository;
use App\Repository\CountryRepository;
use App\Repository\Interfaces\CountryRepositoryInterface;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        $this->app->bind(
            OrderTransactRepositoryInterface::class,
            OrderTransactRepository::class
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
        $this->app->bind(
            ProgramSettingRepositoryInterface::class,
            ProgramSettingRepository::class
        );
        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );
        $this->app->bind(
            CartItemRepositoryInterface::class,
            CartItemRepository::class
        );

        $this->app->bind(
            OrderTransactProductRepositoryInterface::class,
            OrderTransactProductRepository::class
        );
        $this->app->bind(
            CountryRepositoryInterface::class,
            CountryRepository::class
        );
        $this->app->bind(
            BusinessNatureRepositoryInterface::class,
            BusinessNatureRepository::class
        );
        
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
