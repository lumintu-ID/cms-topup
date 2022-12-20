<?php

namespace App\Providers\Frontend;

use App\Repository\Frontend\Invoice\InvoiceRepository;
use App\Repository\Frontend\Invoice\InvoiceRepositoryImplement;
use App\Services\Frontend\Invoice\InvoiceService;
use App\Services\Frontend\Invoice\InvoiceServiceImplement;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        InvoiceRepository::class => InvoiceRepositoryImplement::class,
        InvoiceService::class => InvoiceServiceImplement::class,
    ];

    public function provides(): array
    {
        return [
            InvoiceRepository::class,
            InvoiceService::class
        ];
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
