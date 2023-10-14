<?php

namespace App\Providers;

use App\Interfaces\Core\Payment;
use App\Services\PaymentService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
       Payment::class => PaymentService::class
    ];

    public function provides()
    {
        return [
            PaymentService::class
        ];
    }
    /**
     * Register services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
