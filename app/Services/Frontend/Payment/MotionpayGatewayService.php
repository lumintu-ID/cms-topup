<?php

namespace App\Services\Frontend\Payment;

class MotionpayGatewayService extends PaymentGatewayService
{

  public function generateDataParse(array $dataPayment)
  {
    return;
  }

  public function generateSignature($plaintext)
  {
    return 'generate sinature for motionpay';
  }
}
