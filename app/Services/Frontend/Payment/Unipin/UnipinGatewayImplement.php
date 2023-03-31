<?php

namespace App\Services\Frontend\Payment\Unipin;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class UnipinGatewayImplement implements UnipinGatewayService
{
  private $_guid, $_secretKey, $_urlPayment, $_methodActionPost, $_currencyIDR, $_urlNotify;

  public function __construct()
  {
    $this->_methodActionPost = 'POST';
    $this->_urlNotify = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
    $this->_guid = env('UNIPIN_GUID_DEVELOPMENT');
    $this->_secretKey = env('UNIPIN_SECRET_KEY_DEVELOPMENT');
    $this->_urlPayment = env('UNIPIN_URL_DEVELPOMENT');
    $this->_currencyIDR = 'IDR';
  }

  public function generateDataParse(array $dataPayment, array $dataGame = null)
  {
    $dataAttribute = [
      ['methodAction' => $this->_methodActionPost],
      ['urlAction' => route('payment.parse.vendor', strtolower($dataPayment['code_payment']))],
      ['reference' => $dataPayment['invoice']],
      ['remark' => $dataGame['game_title']],
      ['total_price' => $dataPayment['total_price']],
      ['description' => $dataPayment['amount'] . ' ' . $dataPayment['name']],
    ];

    return $dataAttribute;
  }

  public function generateSignature(string $plainText = null)
  {
    $signature = hash('sha256', $plainText);
    return $signature;
  }

  public function urlRedirect($dataParse)
  {
    $guid = $this->_guid;
    $secretKey = $this->_secretKey;
    $currency = $this->_currencyIDR;
    $reference =  $dataParse['reference'];
    $urlAck = $this->_urlNotify;
    $amount = $dataParse['total_price'];
    $denominations = $amount . $dataParse['description'];
    $signature = $this->generateSignature($guid . $reference . $urlAck . $currency . $denominations . $secretKey);

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
      $dataResponse = $this->_doRequestToApi($payload);

      if (!$this->checkSignature($dataResponse)) throw new Exception('Invalid Signature', 403);
      if ($dataResponse['url']) return $dataResponse['url'];
    } catch (RequestException $error) {
      echo 'Error message: ' . $error->getCode() . ' ' . $error->getResponse()->getReasonPhrase();
    }
  }

  private function _doRequestToApi(array $payload)
  {
    $client = new Client();
    $response = $client->request($this->_methodActionPost, $this->_urlPayment, [
      'headers' => ['Content-type' => 'application/json'],
      'body' => json_encode($payload),
    ]);

    return json_decode($response->getBody()->getContents(), true);
  }

  public function checkSignature($dataResponse)
  {
    $signature = $this->generateSignature($dataResponse['status'] . $dataResponse['message'] . $dataResponse['url'] . $this->_secretKey);

    if ($signature == $dataResponse['signature']) return true;

    return false;
  }
}
