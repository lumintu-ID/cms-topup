<?php

namespace App\Services\Frontend\Payment\Razer;

use App\Models\Reference;
use App\Services\Frontend\Payment\Razer\RazerGatewayService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class RazerGatewayImplement implements RazerGatewayService
{
  private $_applicationCode, $_version, $_hashType, $_addZero, $_urlPayment, $_urlReturn, $_methodActionPost;

  public function __construct()
  {
    $this->_methodActionPost = 'POST';
    $this->_version = 'v1';
    $this->_hashType = 'hmac-sha256';
    $this->_applicationCode = env("RAZOR_MERCHANT_CODE");
    $this->_urlPayment = env("RAZOR_URL_DEVELPOMENT");
    $this->_urlReturn = route('home');
    $this->_addZero = "00";
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
      ['methodAction' => $this->_methodActionPost],
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
      $plainText = $dataParse['amount'] . $this->_addZero
        . $this->_applicationCode
        . $dataParse['currencyCode']
        . $dataParse['customerId']
        . $dataParse['description']
        . $this->_hashType
        . $dataParse['referenceId']
        . $this->_urlReturn
        . $this->_version;

      $response = $this->_doRequestToApi($dataParse, $plainText);
      $dataResponse = json_decode($response->getBody()->getContents(), true);

      if (!$this->checkSignature($dataResponse)) {
        throw new Exception('Invalid Signature', 403);
      }

      if ($dataResponse['paymentUrl']) {
        $this->saveReference($dataResponse['paymentId'], $dataResponse['referenceId']);
        return $dataResponse['paymentUrl'];
      }
    } catch (RequestException $error) {
      $responseError = json_decode($error->getResponse()->getBody()->getContents(), true);
      echo 'Error message ' . $responseError['message'];
    }
  }

  public function generateSignature(string $plainText = null)
  {
    $signature = hash_hmac('sha256', $plainText, env("RAZOR_SECRET_KEY"));
    return $signature;
  }

  public function checkSignature($dataResponse)
  {
    $plainText = $dataResponse['amount']
      . $dataResponse['applicationCode']
      . $dataResponse['currencyCode']
      . $dataResponse['hashType']
      . $dataResponse['paymentId']
      . $dataResponse['paymentUrl']
      . $dataResponse['referenceId']
      . $dataResponse['version'];
    $signatureMerchat = $this->generateSignature($plainText);

    if ($dataResponse['signature'] == $signatureMerchat) return true;

    return false;
  }

  public function saveReference(string $paymentId, string $orderId)
  {
    DB::beginTransaction();
    try {
      $checkInvoice = Reference::where('invoice', $orderId)->first();
      if ($checkInvoice) return;
      Reference::create(['invoice' => $orderId, 'reference' => $paymentId]);
      DB::commit();
      return;
    } catch (\Throwable $th) {
      DB::rollback();
      abort(500, 'Internal error, please try again');
    }
  }

  private function _doRequestToApi(array $dataParse, string $plainText)
  {
    $client = new Client();
    $response = $client->request($this->_methodActionPost, $this->_urlPayment, [
      'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
      'form_params' => [
        "applicationCode" => $this->_applicationCode,
        "referenceId" => $dataParse['referenceId'],
        "version" => $this->_version,
        "amount" => $dataParse['amount'] . $this->_addZero,
        "currencyCode" => $dataParse['currencyCode'],
        "returnUrl" => $this->_urlReturn,
        "description" => $dataParse['description'],
        "customerId" => $dataParse['customerId'],
        "hashType" => $this->_hashType,
        "signature" => $this->generateSignature($plainText),
      ]
    ]);

    return $response;
  }
}
