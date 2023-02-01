<?php

namespace App\Services\Frontend\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RazorGateWayService extends PaymentGatewayService
{
  private $_applicationCode;
  private $_version;
  private $_hashType;

  public function __construct()
  {
    $this->_applicationCode = ENV("RAZOR_MERCHANT_CODE");
    $this->urlPayment = env("RAZOR_URL_DEVELPOMENT");
    $this->_version = 'v1';
    $this->urlReturn = route('home');
    $this->_hashType = 'hmac-sha256';
  }

  public function generateDataParse(array $dataPayment)
  {
    $urlAction = route('payment.parse.vendor', strtolower($dataPayment['code_payment']));
    $referenceId = $dataPayment['invoice'];
    $amount = $dataPayment['total_price'];
    $customerId = $dataPayment['user'];
    $currencyCode = 'IDR';
    $description = $dataPayment['amount'] . ' ' . $dataPayment['name'];

    $dataAttribute = [
      ['methodAction' => $this->methodActionPost],
      ['urlAction' => $urlAction],
      ['referenceId' => $referenceId],
      ['amount' => $amount],
      ['currencyCode' => $currencyCode],
      ['description' => $description],
      ['customerId' => $customerId]
    ];

    return $dataAttribute;
  }

  public function urlRedirect(array $dataParse)
  {
    try {
      $plainText = $dataParse['amount']
        . $this->_applicationCode
        . $dataParse['currencyCode']
        . $dataParse['customerId']
        . $dataParse['description']
        . $this->_hashType
        . $dataParse['referenceId']
        . $this->urlReturn
        . $this->_version;

      $client = new Client();
      $response = $client->request('POST', $this->urlPayment, [
        'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
        'form_params' => [
          "applicationCode" => $this->_applicationCode,
          "referenceId" => $dataParse['referenceId'],
          "version" => $this->_version,
          "amount" => $dataParse['amount'],
          "currencyCode" => $dataParse['currencyCode'],
          "returnUrl" => $this->urlReturn,
          "description" => $dataParse['description'],
          "customerId" => $dataParse['customerId'],
          "hashType" => $this->_hashType,
          "signature" => $this->generateSignature($plainText),
        ]
      ]);
      $dataResponse = json_decode($response->getBody()->getContents(), true);
      if ($dataResponse['paymentUrl']) return $dataResponse['paymentUrl'];
    } catch (RequestException $error) {
      $responseError = json_decode($error->getResponse()->getBody()->getContents(), true);
      echo 'Error message ' . $responseError['message'];
    }
  }

  public function generateSignature(string $plainText)
  {
    $signature = hash_hmac('sha256', $plainText, env("RAZOR_SECRET_KEY"));
    return $signature;
  }
}
