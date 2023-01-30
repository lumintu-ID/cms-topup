<?php

namespace App\Services\Frontend\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class MotionpayGatewayService extends PaymentGatewayService
{
  private $_dateTime;
  private $_merchantCode;
  private $_secretKey;
  private $_timeLimit;

  public function __construct()
  {
    $this->urlPayment = env("MOTIONPAY_URL_DEVELOPMENT");
    $this->_merchantCode = env("MOTIONPAY_MERCHANT_CODE");
    $this->_secretKey = env("MOTIONPAY_SECRET_KEY");
    $this->_timeLimit = "60";
  }

  public function generateDataParse(array $dataPayment)
  {

    // // dd($dataPayment);

    // $merchantCode = $this->_merchantCode;
    // $firstName = $dataPayment['user'];
    // $lastName = $dataPayment['user'];
    // $email = $dataPayment['email'];
    // $phone = $dataPayment['phone'] ?? '082119673393';
    // $orderId = $dataPayment['invoice'];
    // $numberReference = $dataPayment['invoice'];
    // $amount = (string)$dataPayment['amount'];
    // $currency = $this->currency;
    // $itemDetails =  $dataPayment['amount'] . '|' . $dataPayment['name'];
    // $datetimeRequest = $this->_dateTime;
    // $paymentMethod = 'ALL';
    // $timeLimit = $this->_timeLimit;
    // $thanksUrl = route('home');
    // $notifUrl = $this->urlNotify;
    // $plainText = $merchantCode
    //   . $firstName
    //   . $lastName
    //   . $email
    //   . $phone
    //   . $orderId
    //   . $numberReference
    //   . $amount
    //   . $currency
    //   . $itemDetails
    //   . $datetimeRequest
    //   . $paymentMethod
    //   . $timeLimit
    //   . $notifUrl
    //   . $thanksUrl
    //   . $this->_secretKey;

    // $payload = [
    //   'merchant_code' => $merchantCode,
    //   'first_name' => $firstName,
    //   'last_name' => $lastName,
    //   'email' => $email,
    //   'phone' => $phone,
    //   'order_id' => $orderId,
    //   'no_reference' => $numberReference,
    //   'amount' => $amount,
    //   'currency' => $currency,
    //   'item_details' => $itemDetails,
    //   'datetime_request' => $datetimeRequest,
    //   'payment_method' => $paymentMethod,
    //   'time_limit' => $timeLimit,
    //   'notif_url' => $notifUrl,
    //   'thanks_url' => $thanksUrl,
    //   'signature' => $this->generateSignature($plainText)
    // ];

    // $responseDummy = [
    //   "trans_id" => "xridvdl3n2",
    //   "merchant_code" => "FmSample",
    //   "order_id" => "INV-wBqwRSXeR9YU",
    //   "no_reference" => "INV-wBqwRSXeR9YU",
    //   "amount" => "10000",
    //   "frontend_url" => "https://playpay.flashmobile.co.id",
    //   "signature" => "93ef66e8f30f1ed5e73afdb00b412cc538eed7f2",
    // ];

    // $dataParse = [
    //   'methodAction' => $this->methodActionPost,
    //   'url' => $responseDummy['frontend_url'],
    //   'idForm' => 'formRedirectMp',
    //   'inputElement' => [
    //     'trans_id',
    //     'merchant_code',
    //     'order_id',
    //     'signature',
    //   ]
    // ];

    // $dataAttribute = [
    //   'methodAction' => $this->methodActionPost,
    //   'urlAction' => $dataPayment['url'],
    //   'dataParse' => $dataParse,
    //   // 'dataRedirectTo' => $dataRedirectTo
    // ];

    // $dataAttribute = [
    //   // ['methodAction' => $this->methodActionPost],
    //   // ['urlAction' => route('payment.parse.vendor', strtolower($dataPayment['code_payment']))],
    //   // ['amount' => $dataPayment['amount']],
    //   // ['name' => $dataPayment['name']],
    //   // ['total_price' => $dataPayment['total_price']],
    //   // ['first_name' => $dataPayment['user']],
    //   // ['last_name' => $dataPayment['user']],
    //   // ['email' => $dataPayment['email']],
    //   // ['phone' => $dataPayment['phone'] ?? '082119673393'],
    //   // ['invoice' => $dataPayment['invoice']],
    //   // ['item_details' => $dataPayment['amount'] . '|' . $dataPayment['name']]
    //   // 'dataParse' => $responseDummy
    // ];

    $responseDummy = [
      "trans_id" => "xridvdl3n2",
      "merchant_code" => "FmSample",
      "order_id" => "INV-wBqwRSXeR9YU",
      "no_reference" => "INV-wBqwRSXeR9YU",
      "amount" => "10000",
      "frontend_url" => "https://playpay.flashmobile.co.id",
      "signature" => "93ef66e8f30f1ed5e73afdb00b412cc538eed7f2",
    ];

    $dataAttribute = [
      ['methodAction' => $this->methodActionPost],
      ['urlAction' => $responseDummy['frontend_url']],
      ['trans_id' => $responseDummy['trans_id']],
      ['merchant_code' => $responseDummy['merchant_code']],
      ['order_id' => $responseDummy['order_id']],
      ['signature' => $responseDummy['signature']],
    ];

    return $dataAttribute;
  }

  public function urlRedirect(array $dataParse)
  {
    $this->_dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('YmdHis');
    $merchantCode = $this->_merchantCode;
    $firstName = $dataParse['first_name'];
    $lastName = $dataParse['last_name'];
    $email = $dataParse['email'];
    $phone = $dataParse['phone'] ?? '082119673393';
    $orderId = $dataParse['invoice'];
    $numberReference = $dataParse['invoice'];
    $amount = (string)$dataParse['total_price'];
    $currency = $this->currency;
    $itemDetails =  $dataParse['amount'] . '|' . $dataParse['name'];
    $paymentMethod = 'ALL';
    $thanksUrl = route('home');
    $plainText = $merchantCode
      . $firstName
      . $lastName
      . $email
      . $phone
      . $orderId
      . $numberReference
      . $amount
      . $currency
      . $itemDetails
      . $this->_dateTime
      . $paymentMethod
      . $this->_timeLimit
      . $this->urlNotify
      . $thanksUrl
      . $this->_secretKey;

    $payload = [
      'merchant_code' => $merchantCode,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'email' => $email,
      'phone' => $phone,
      'order_id' => $orderId,
      'no_reference' => $numberReference,
      'amount' => $amount,
      'currency' => $currency,
      'item_details' => $itemDetails,
      'datetime_request' => $this->_dateTime,
      'payment_method' => $paymentMethod,
      'time_limit' => $this->_timeLimit,
      'notif_url' => $this->urlNotify,
      'thanks_url' => $thanksUrl,
      'signature' => $this->generateSignature($plainText)
    ];
    // $payload = [
    //   'merchant_code' => $this->_merchantCode,
    //   'first_name' => $firstName,
    //   'last_name' => $lastName,
    //   'email' => $email,
    //   'phone' => $phone,
    //   'order_id' => $orderId,
    //   'no_reference' => $numberReference,
    //   'amount' => $amount,
    //   'currency' => $currency,
    //   'item_details' => $itemDetails,
    //   'datetime_request' => $datetimeRequest,
    //   'payment_method' => $paymentMethod,
    //   'time_limit' => $timeLimit,
    //   'notif_url' => $notifUrl,
    //   'thanks_url' => $thanksUrl,
    //   'signature' => $this->generateSignature($plainText)
    // ];

    // dd($payload);
    // dd(json_encode($payload));

    // $responseDummy = [
    //   "trans_id" => "xridvdl3n2",
    //   "merchant_code" => "FmSample",
    //   "order_id" => "INV-wBqwRSXeR9YU",
    //   "no_reference" => "INV-wBqwRSXeR9YU",
    //   "amount" => "10000",
    //   "frontend_url" => "https://playpay.flashmobile.co.id",
    //   "signature" => "93ef66e8f30f1ed5e73afdb00b412cc538eed7f2",
    // ];

    // $response = Http::post('https://playpay.flashmobile.co.id', [
    //   "trans_id" => "xridvdl3n2",
    //   "merchant_code" => "FmSample",
    //   "order_id" => "INV-wBqwRSXeR9YU",
    //   "no_reference" => "INV-wBqwRSXeR9YU",
    //   "amount" => "10000",
    //   "signature" => "93ef66e8f30f1ed5e73afdb00b412cc538eed7f2",
    // ]);

    // $res = json_decode($response->getBody()->getContents(), true);

    // dd($response);
    // dd($res);

    // return $this->_redirectToMontionpay($responseDummy);
    die;
    try {
      $client = new Client();
      $response = $client->request('POST', $this->urlPayment, [
        'headers' => ['Content-type' => 'application/json'],
        'body' => json_encode($payload),
      ]);
      $dataResponse = json_decode($response->getBody()->getContents(), true);
      return $this->_redirectToMontionpay($dataResponse);
    } catch (RequestException $error) {
      // $responseError = json_decode($error->getResponse()->getBody()->getContents(), true);
      echo 'Error message: ' . $error;
    }
  }

  public function generateSignature($plainText)
  {
    $signature = hash('sha1', md5($plainText));
    return $signature;
  }

  private function _redirectToMontionpay($data = null)
  {
    return  $data['frontend_url'];

    if (!empty($data)) {

      $client = new Client();
      $response = $client->request('post', $data['frontend_url'], [
        'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
        'form_params' => [
          "trans_id" => "xridvdl3n2",
          "merchant_code" => "FmSample",
          "order_id" => "INV-wBqwRSXeR9YU",
          "no_reference" => "INV-wBqwRSXeR9YU",
          "amount" => "10000",
          "frontend_url" => "https://playpay.flashmobile.co.id",
          "signature" => "93ef66e8f30f1ed5e73afdb00b412cc538eed7f2",
        ]
      ]);
      $dataResponse = json_decode($response->getBody()->getContents(), true);

      // dd($dataResponse);
    }
  }
}
