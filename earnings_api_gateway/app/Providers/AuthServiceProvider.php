<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;
use App\Providers\CacheUserProvider;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPolicies();
        // Passport::routes();
        // Passport::routes(function(RouteRegistrar $router){
        //     $router->forAccessTokens();
            
        // },['middleware'=>'internalJwtMiddleware']);
        Auth::provider('eloquent-cache',function($app,array $config){
            return new CacheUserProvider($app['hash'],$config['model']);
        });

        Passport::routes(function(RouteRegistrar $router){
            $router->forAccessTokens();
            
            
        });
        Passport::tokensExpireIn(now()->addMinutes(config('jwt.ttl')));
        // Passport::tokensCan([
        //     'user' => 'User Type',
        //     'admin' => 'Admin User Type',
        // ]);
        // if (! $this->app->routesAreCached()) {
        //     Passport::routes();
        // }
        // Passport::routes(function (RouteRegistrar $router) {
        //     $router->forAccessTokens();
        // }, ['prefix' => 'api/oauth', 'middleware' => 'passport-administrators']);

        // Passport::tokensExpireIn(now()->addDay(3));
        // Passport::refreshTokensExpireIn(now()->addDay(3));


    }
}
