<?php

namespace App\Services\Frontend\Payment\Coda;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;

class CodaGatewayImplement implements CodaGatewayService
{
  private $_methodActionPost, $_urlPayment;

  public function __construct()
  {
    $this->_methodActionPost = "POST";
    $this->_urlPayment = env('CODA_URL_DEVELOPMENT');
  }

  public function generateDataParse(array $dataPayment)
  {
    $dataAttribute = [
      ['methodAction' => $this->_methodActionPost],
      ['urlAction' => route('payment.parse.vendor', strtolower($dataPayment['code_payment']))],
      ['orderId' => $dataPayment['invoice']],
      ['codePayment' => $dataPayment['code_payment']],
      ['channelId' => $dataPayment['channel_id']],
      ['name' => $dataPayment['amount'] . ' ' . $dataPayment['name']],
      ['priceId' => $dataPayment['price_id']],
      ['price' => $dataPayment['total_price']],
      ['user_id' => $dataPayment['user']],
    ];

    return $dataAttribute;
  }

  public function urlRedirect(array $dataParse)
  {
    $apiKey = base64_encode(env("CODA_API_KEY"));
    $keyAppFe = hash('md5', env('FRONTEND_APP_KEY'));
    $initRequest['initRequest'] = [
      'country' => 360,
      'currency' => 360,
      'orderId' => $dataParse['orderId'],
      'apiKey' => env("CODA_API_KEY"),
      // 'key' => base64_encode($keyAppFe . '-' . $apiKey),
      'payType' => $dataParse['channelId'],
      'items' => [
        "code" => $dataParse['priceId'],
        "name" => $dataParse['name'],
        "price" => number_format($dataParse['price'], 2, '.', ''),
        "type" =>  1,
      ],
      'profile' => [
        'entry' => [
          [
            "key" => 'user_id',
            "value" => $dataParse['user_id'],
          ],
          [
            "key" => 'need_mno_id',
            "value" => 'yes',
          ],
        ]
      ],
    ];

    // dd($initRequest);
    $response = $this->_doRequestToApi($initRequest);
    // $dataResponse = json_decode($response->getBody()->getContents(), true);
    // dd($response['initResult']);
    if ($response['initResult']['resultCode'] == 0) {
      $url = 'https://sandbox.codapayments.com/airtime/begin?type=3&txn_id=' . $response['initResult']['txnId'];
      return $url;
    }

    // if()

    return json_encode($initRequest);
  }

  private function _doRequestToApi(array $payload)
  {
    $client = new Client();
    $response = $client->request($this->_methodActionPost, $this->_urlPayment, [
      'headers' => ['Content-type' => 'application/json'],
      'body' => json_encode($payload),
    ]);
    // $dataResponse = json_decode($response->getBody()->getContents(), true);

    // dd($dataResponse);

    return json_decode($response->getBody()->getContents(), true);
  }
}
