<?php

namespace App\Services\Frontend\Payment;


class GocpayGatewayService extends PaymentGatewayService
{
  private $_merchantId, $_haskey, $_trxDateTime;

  public function __construct()
  {
    $this->_merchantId = env('GOC_MERCHANT_ID');
    $this->_haskey = env('GOC_HASHKEY');
    $this->urlReturn = route('home');
    $this->urlPayment = env('GOC_URL_DEVELOPMENT');
  }

  public function generateDataParse($dataPayment)
  {
    $this->_trxDateTime = substr(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('Y-m-d\TH:i:sP'), 0, -3);
    $plainText = $this->_merchantId
      . $dataPayment['invoice']
      . $this->_trxDateTime
      . $dataPayment['channel_id']
      . $dataPayment['total_price']
      . $this->currencyIDR
      . $this->_haskey;

    $dataAttribute = [
      ['urlAction' => $this->urlPayment ?? $dataPayment['url']],
      ['methodAction' => $this->methodActionPost],
      ['merchantId' => $this->_merchantId],
      ['trxId' => $dataPayment['invoice']],
      ['trxDateTime' => $this->_trxDateTime],
      ['channelId' => $dataPayment['channel_id']],
      ['amount' => $dataPayment['total_price']],
      ['currency' => $this->currencyIDR],
      ['returnUrl' => $this->urlReturn],
      ['name' => 'name'],
      ['email' => $dataPayment['email']],
      ['phone' => $dataPayment['phone'] ?? null],
      ['userId' => 'userId'],
      ['sign' => $this->generateSignature($plainText)],
    ];

    return $dataAttribute;
  }

  public function generateSignature($plainText)
  {
    $signature = hash('sha256', $plainText);
    return $signature;
  }
}
