<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use App\Events\OrderDetailsShipmentStatusChange;
use App\Listeners\SendOrderDetailsShipmentStatusChangeNotification;
use App\Models\OrderDetail;
use App\Observers\OrderDetailObserver;


class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    Registered::class => [
        SendEmailVerificationNotification::class,
    ],

    SocialiteWasCalled::class => [
        \SocialiteProviders\Apple\AppleExtendSocialite::class.'@handle',
    ],

    OrderDetailsShipmentStatusChange::class => [
        SendOrderDetailsShipmentStatusChangeNotification::class,
    ],
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    //
  }
}
