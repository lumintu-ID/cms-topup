<?php

namespace App\Providers\Frontend;

use App\Repository\Frontend\Payment\PaymentRepository;
use App\Repository\Frontend\Payment\PaymentRepositoryImplement;
use App\Services\Frontend\Payment\PaymentService;
use App\Services\Frontend\Payment\PaymentServiceImplement;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        PaymentRepository::class => PaymentRepositoryImplement::class,
        PaymentService::class => PaymentServiceImplement::class,
    ];

    public function provides(): array
    {
        return [
            PaymentRepository::class,
            PaymentService::class
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
