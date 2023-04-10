<?php

namespace App\Services\Frontend\Payment\Gocpay;

class GocpayGatewayImplement extends GocpayGatewayService
{

  private $_merchantId, $_haskey, $_trxDateTime;

  public function __construct()
  {
    $this->_merchantId = env('GOC_MERCHANT_ID');
    $this->_haskey = env('GOC_HASHKEY');
    $this->urlPayment = env('GOC_URL_DEVELOPMENT');
    $this->urlReturn = route('home');
  }

  public function generateDataParse(array $dataPayment)
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

  public function generateSignature(string $plainText = null)
  {
    $signature = hash('sha256', $plainText);
    return $signature;
  }
}