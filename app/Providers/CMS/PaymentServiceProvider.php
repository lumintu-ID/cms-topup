<?php

namespace App\Providers\CMS;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/GOC.php';
        require_once app_path() . '/Helpers/Unipin.php';
        require_once app_path() . '/Helpers/GudangVoucher.php';
        require_once app_path() . '/Helpers/MotionPay.php';
        require_once app_path() . '/Helpers/Razor.php';
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
