<?php

namespace DTApi\Providers;


use DTApi\Contracts\Bookings\BookingInterface;
use DTApi\Contracts\Bookings\NotificationInterface;
use DTApi\Repository\Bookings\BookingRepository;
use DTApi\Repository\Bookings\NotificationRepository;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(BookingInterface::class, BookingRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationRepository::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
