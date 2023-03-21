<?php

namespace App\Services\Frontend\Payment\Gocpay;

abstract class GocpayGatewayService
{
  protected $urlReturn, $urlPayment, $codePayment;
  protected $urlNotify = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
  protected $methodActionPost = 'POST';
  protected $methodActionGet = 'GET';
  protected $currencyIDR = 'IDR';

  abstract public function generateDataParse(array $dataPayment);
  abstract public function generateSignature(string $plaintText);
}
