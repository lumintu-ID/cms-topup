<?php

namespace App\Service\Frontend\Payment;

class PaymentGatewayService
{
  protected $urlReturn;
  protected $urlNotify;
  protected $methodActionPost;
  protected $methodActionGet;
  protected $codePayment;

  public function __construct()
  {
    $this->methodActionGet = 'GET';
    $this->methodActionPost = 'POST';
  }

  public function generateSignature()
  {
    return 'generate signature';
  }

  public function getDate()
  {
    return 'get date or time';
  }
}
