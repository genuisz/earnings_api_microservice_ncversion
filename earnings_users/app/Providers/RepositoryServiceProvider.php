<?php

namespace App\Providers;

use App\Repository\AddressBookRepository;
use App\Repository\Interfaces\AddressBookRepositoryInterface;
use App\Repository\Interfaces\BusinessNatureRepositoryInterface;
use App\Repository\Interfaces\CountryRepositoryInterface;
use App\Repository\Interfaces\ProgramSettingRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\Interfaces\UserInfoRepositoryInterface;
use App\Repository\NonLocalRepository\BusinessRepository;
use App\Repository\NonLocalRepository\CountryRepository;
use App\Repository\ProgramSettingRepository;

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
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            UserInfoRepositoryInterface::class,
            UserInfoRepository::class
        );
        $this->app->bind(
            ProgramSettingRepositoryInterface::class,
            ProgramSettingRepository::class
        );
        $this->app->bind(
            CountryRepositoryInterface::class,
            CountryRepository::class
        );
        $this->app->bind(
            BusinessNatureRepositoryInterface::class,
            BusinessRepository::class
        );
        $this->app->bind(
            AddressBookRepositoryInterface::class,
            AddressBookRepository::class
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
