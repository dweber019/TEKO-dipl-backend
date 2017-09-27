<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

use Auth0\SDK\Helpers\Cache\CacheHandler as CacheHandler;
use Auth0\Login\Contract\Auth0UserRepository as Auth0UserRepositoryContract;
use Auth0\Login\Repository\Auth0UserRepository as Auth0UserRepository;
use App\Repository\AuthUserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
          Auth0UserRepositoryContract::class,
          AuthUserRepository::class);

        // This is used for RS256 tokens to avoid fetching the JWKs on each request
        $this->app->bind(
          CacheHandler::class,
          function () {
              static $cacheWrapper = null;
              if ($cacheWrapper === null) {
                  $cache = Cache::store();
                  $cacheWrapper = new LaravelCacheWrapper($cache);
              }

              return $cacheWrapper;
          }
        );
    }
}
