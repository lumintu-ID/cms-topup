<?php

namespace App\Providers\Frontend;

use App\Repository\Frontend\Payment\PaymentRepository;
use App\Repository\Frontend\Payment\PaymentRepositoryImplement;
use App\Services\Frontend\Payment\PaymentService;
use App\Services\Frontend\Payment\PaymentServiceImplement;


// Repositories Payment Gateway
use App\Repository\Frontend\Payment\Motionpay\MotionpayRepository;
use App\Repository\Frontend\Payment\Motionpay\MotionpayRepositoryImplement;

// Services Payment Gateway
use App\Services\Frontend\Payment\Gocpay\GocpayGatewayImplement;
use App\Services\Frontend\Payment\Gocpay\GocpayGatewayService;
use App\Services\Frontend\Payment\GudangVoucher\GudangVoucherGatewayImplement;
use App\Services\Frontend\Payment\GudangVoucher\GudangVoucherGatewayService;
use App\Services\Frontend\Payment\Motionpay\MotionpayGatewayImplement;
use App\Services\Frontend\Payment\Motionpay\MotionpayGatewayService;
use App\Services\Frontend\Payment\Razer\RazerGatewayImplement;
use App\Services\Frontend\Payment\Razer\RazerGatewayService;
use App\Services\Frontend\Payment\Unipin\UnipinGatewayImplement;
use App\Services\Frontend\Payment\Unipin\UnipinGatewayService;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public array $singletons = [
        PaymentRepository::class => PaymentRepositoryImplement::class,
        MotionpayRepository::class => MotionpayRepositoryImplement::class,
        PaymentService::class => PaymentServiceImplement::class,
        GocpayGatewayService::class => GocpayGatewayImplement::class,
        GudangVoucherGatewayService::class => GudangVoucherGatewayImplement::class,
        MotionpayGatewayService::class => MotionpayGatewayImplement::class,
        RazerGateWayService::class => RazerGatewayImplement::class,
        UnipinGateWayService::class => UnipinGatewayImplement::class,
    ];


    public function provides(): array
    {
        return [
            PaymentRepository::class,
            PaymentService::class,
            GocpayGatewayService::class,
            GudangVoucherGatewayService::class,
            MotionpayGatewayService::class,
            RazerGateWayService::class,
            UnipinGatewayService::class,
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
