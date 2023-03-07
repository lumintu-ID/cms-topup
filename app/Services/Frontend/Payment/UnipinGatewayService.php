<?php

namespace App\Services\Frontend\Payment;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class UnipinGatewayService extends PaymentGatewayService
{
  private $_guid, $_secretKey;

  public function __construct()
  {
    $this->_guid = env('UNIPIN_GUID_DEVELOPMENT');
    $this->_secretKey = env('UNIPIN_SECRET_KEY_DEVELOPMENT');
    $this->urlPayment = env('UNIPIN_URL_DEVELPOMENT');
  }

  public function generateDataParse(array $dataPayment, array $dataGame = null)
  {
    $urlAction = route('payment.parse.vendor', strtolower($dataPayment['code_payment']));
    $reference =  $dataPayment['invoice'];
    $remark = $dataGame['game_title'];
    $dataAttribute = [
      ['methodAction' => $this->methodActionPost],
      ['urlAction' => $urlAction],
      ['reference' => $reference],
      ['remark' => $remark],
      ['total_price' => $dataPayment['total_price']],
      ['description' => $dataPayment['amount'] . ' ' . $dataPayment['name']],
    ];

    return $dataAttribute;
  }

  public function urlRedirect($dataParse)
  {
    $guid = $this->_guid;
    $secretKey = $this->_secretKey;
    $currency = $this->currencyIDR;
    $reference =  $dataParse['reference'];
    $urlAck = $this->urlNotify;
    $amount = $dataParse['total_price'];
    $denominations = $amount . $dataParse['description'];
    $signature = hash('sha256', $guid . $reference . $urlAck . $currency . $denominations . $secretKey);

    $payload = [
      'guid' => $guid,
      'reference' => $reference,
      'urlAck' => $urlAck,
      'currency' => $currency,
      'remark' => $dataParse['remark'],
      'signature' => $signature,
      'denominations' => [
        [
          'amount' => $amount,
          'description' => $dataParse['description']
        ]
      ]
    ];

    try {
      $client = new Client();
      $response = $client->request('POST', $this->urlPayment, [
        'headers' => ['Content-type' => 'application/json'],
        'body' => json_encode($payload),
      ]);
      $dataResponse = json_decode($response->getBody()->getContents(), true);

      if (!$this->_checkSignature($dataResponse)) throw new Exception('Invalid Signature', 403);
      if ($dataResponse['url']) return $dataResponse['url'];
    } catch (RequestException $error) {
      echo 'Error message: ' . $error->getCode() . ' ' . $error->getResponse()->getReasonPhrase();
    }
  }

  private function _checkSignature($dataResponse)
  {
    $signature = hash('sha256', $dataResponse['status'] . $dataResponse['message'] . $dataResponse['url'] . $this->_secretKey);

    if ($signature == $dataResponse['signature']) return true;

    return false;
  }
}
