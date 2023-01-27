<?php

namespace App\Providers\Frontend;

use App\Repository\Frontend\Payment\PaymentRepository;
use App\Repository\Frontend\Payment\PaymentRepositoryImplement;
use App\Services\Frontend\Payment\MotionpayGatewayService;
use App\Services\Frontend\Payment\PaymentGatewayService;
use App\Services\Frontend\Payment\RazorGateWayService;
use App\Services\Frontend\Payment\PaymentService;
use App\Services\Frontend\Payment\PaymentServiceImplement;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public array $singletons = [
        PaymentRepository::class => PaymentRepositoryImplement::class,
        PaymentService::class => PaymentServiceImplement::class,
        PaymentGatewayService::class => RazorGateWayService::class,
        PaymentGatewayService::class => MotionpayGatewayService::class
    ];


    public function provides(): array
    {
        return [
            PaymentRepository::class,
            PaymentService::class,
            RazorGateWayService::class,
            MotionpayGatewayService::class,
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
