<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use App\Security\Sha3Hasher;


class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Schema::defaultStringLength(191);
    Paginator::useBootstrap();
    View::addNamespace('seller', resource_path('views/seller'));
    RateLimiter::for('global', function ($request) {
      return Limit::perMinute(100)->by($request->ip()); // 100 requests per minute per IP
    });
    Hash::extend('sha3', function () {
      $config = config('hashing.drivers.sha3');
      return new Sha3Hasher(
          $config['rounds'],
          $config['secret']
      );
    });

  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
