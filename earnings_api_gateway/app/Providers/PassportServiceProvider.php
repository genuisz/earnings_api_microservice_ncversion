<?php
namespace App\Providers;

use App\Auth\BearerTokenResponse;
use Laravel\Passport\Bridge;
use League\OAuth2\Server\AuthorizationServer;
use App\Repository\ClientRepository;
use App\Repository\TokenRepository;
use Illuminate\Auth\RequestGuard;
use Laravel\Passport\Guards\TokenGuard;
use Laravel\Passport\PassportUserProvider;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\ResourceServer;
use Laravel\Passport\Passport;
use Illuminate\Config\Repository as Config;
class PassportServiceProvider extends \Laravel\Passport\PassportServiceProvider
{

    public function register()
    {

        $this->registerTokenRepository();
        $this->registerClientRepository();

 
    }
    /**
    * This method is for binding the App\Repository\ClientRepository ClientRepository in passport ClientRepository
    */
    protected function registerClientRepository()
    {
        $this->app->singleton('Laravel\Passport\ClientRepository', function ($container) {
            $config = $container->make('config')->get('passport.personal_access_client');
            return new ClientRepository($config['id'] ?? null, $config['secret'] ?? null);
        });

    }        
    /**
    * This method is for binding the App\Repository\TokenRepository TokenRepository in passport TokenRepository
    */

    protected function registerTokenRepository(){
        $this->app->singleton('Laravel\Passport\TokenRepository', function ($container) {
            return new TokenRepository();
        });
    }




    // protected function makeGuard(array $config)
    // {

        
    //     return new RequestGuard(function ($request) use ($config) {
    //         return (new TokenGuard(
    //             $this->app->make(ResourceServer::class),
    //             new PassportUserProvider(Auth::createUserProvider($config['provider']), $config['provider']),
    //             $this->app->make(TokenRepository::class),
    //             $this->app->make(ClientRepository::class),
    //             $this->app->make('encrypter')
    //         ))->user($request);
    //     }, $this->app['request']);
    // }
}