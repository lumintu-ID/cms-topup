<?php

namespace App\Providers\Frontend;

use App\Repository\Frontend\Payment\PaymentRepository;
use App\Repository\Frontend\Payment\PaymentRepositoryImplement;
use App\Services\Frontend\Payment\PaymentService;
use App\Services\Frontend\Payment\PaymentServiceImplement;
use App\Services\Frontend\Payment\PaymentGatewayService;
use App\Services\Frontend\Payment\MotionpayGatewayService;

use App\Services\Frontend\Payment\GudangVoucher\GudangVoucherGatewayService;
use App\Services\Frontend\Payment\GudangVoucher\GudangVoucherGatewayImplement;
use App\Services\Frontend\Payment\Gocpay\GocpayGatewayService;
use App\Services\Frontend\Payment\Gocpay\GocpayGatewayImplement;
use App\Services\Frontend\Payment\Razer\RazerGatewayService;
use App\Services\Frontend\Payment\Razer\RazerGatewayImplement;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public array $singletons = [
        PaymentRepository::class => PaymentRepositoryImplement::class,
        PaymentService::class => PaymentServiceImplement::class,
        PaymentGatewayService::class => MotionpayGatewayService::class,
        GocpayGatewayService::class => GocpayGatewayImplement::class,
        GudangVoucherGatewayService::class => GudangVoucherGatewayImplement::class,
        RazerGateWayService::class => RazerGatewayImplement::class,
    ];


    public function provides(): array
    {
        return [
            PaymentRepository::class,
            PaymentService::class,
            RazerGateWayService::class,
            GocpayGatewayService::class,
            GudangVoucherGatewayService::class,
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
