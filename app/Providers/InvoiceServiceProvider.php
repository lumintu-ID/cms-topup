<?php

namespace App\Providers;

use App\Services\Frontend\Invoice\InvoiceService;
use App\Services\Frontend\Invoice\InvoiceServiceImplement;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        InvoiceService::class => InvoiceServiceImplement::class,
    ];

    public function provides(): array
    {
        return [InvoiceService::class];
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
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
