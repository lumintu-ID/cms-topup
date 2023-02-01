<?php

namespace App\Services\Frontend\Payment;

class GudangVoucherGatewayService extends PaymentGatewayService
{
  private $_merchantId, $_mercahtKey;

  public function __construct()
  {
    $this->_merchantId = env('GV_MERCHANT_ID');
    $this->_mercahtKey = env('GV_MERCHANT_KEY');
    $this->urlPayment = env('GV_URL_DEVELOPMENT');
    $this->urlReturn = route('home');
  }

  public function generateDataParse(array $dataPayment)
  {
    $amount = $dataPayment['total_price'];
    $custom = $dataPayment['invoice'];
    $product = $dataPayment['amount'] . ' ' . $dataPayment['name'];
    $email = $dataPayment['email'];
    $plainText = $this->_merchantId . $amount . $this->_mercahtKey . $custom;
    $dataAttribute = [
      ['urlAction' => $this->urlPayment],
      ['methodAction' => $this->methodActionGet],
      ['merchantid' => $this->_merchantId],
      ['custom' => $custom],
      ['product' => $product],
      ['amount' => $amount],
      ['custom_redirect' => $this->urlReturn],
      ['email' => $email],
      ['signature' => $this->generateSignature($plainText)],
    ];

    return $dataAttribute;
  }

  public function generateSignature(string $plainText)
  {
    $signature = hash('md5', $plainText);
    return $signature;
  }
}
