<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Listeners\HandleStripeWebhook;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;

class AppServiceProvider extends ServiceProvider
{

   protected $listen = [
        // doc Cashier : WebhookReceived est dispatché par Cashier
        // pour chaque événement Stripe reçu
        WebhookReceived::class => [
            HandleStripeWebhook::class,
        ],
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}


