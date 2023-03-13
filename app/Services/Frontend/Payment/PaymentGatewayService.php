<?php

namespace App\Services\Frontend\Payment;

abstract class PaymentGatewayService
{
  protected $urlReturn, $urlPayment, $codePayment;
  protected $urlNotify = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
  protected $methodActionPost = 'POST';
  protected $methodActionGet = 'GET';
  protected $currencyIDR = 'IDR';

  abstract protected function generateDataParse(array $dataPayment);
  abstract protected function generateSignature(string $plaintText = null);
}
