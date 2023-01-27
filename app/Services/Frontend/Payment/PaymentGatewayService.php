<?php

namespace App\Services\Frontend\Payment;

class PaymentGatewayService
{
  protected $urlReturn;
  protected $urlNotify;
  protected $urlPayment;
  protected $methodActionPost = 'POST';
  protected $methodActionGet = 'GET';
  protected $codePayment;

  public function __construct()
  {
  }

  protected function generateSignature(string $plaintText)
  {
    return 'generate signature';
  }

  public function getDate()
  {
    return 'get date or time';
  }
}
