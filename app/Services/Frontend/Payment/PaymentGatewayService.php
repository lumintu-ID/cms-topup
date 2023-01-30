<?php

namespace App\Services\Frontend\Payment;

class PaymentGatewayService
{
  protected $urlReturn;
  protected $urlNotify = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
  protected $urlPayment;
  protected $methodActionPost = 'POST';
  protected $methodActionGet = 'GET';
  protected $codePayment;
  protected $currency = 'IDR';

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
