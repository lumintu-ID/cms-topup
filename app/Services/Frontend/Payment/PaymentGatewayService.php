<?php

namespace App\Services\Frontend\Payment;

class PaymentGatewayService
{
  protected $urlReturn, $urlPayment, $codePayment;
  protected $urlNotify = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
  protected $methodActionPost = 'POST';
  protected $methodActionGet = 'GET';
  protected $currencyIDR = 'IDR';

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
