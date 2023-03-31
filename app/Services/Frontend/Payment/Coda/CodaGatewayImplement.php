<?php

namespace App\Services\Frontend\Payment\Coda;

use GuzzleHttp\Client;

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
      ['channelId' => $dataPayment['channel_id']],
      ['name' => $dataPayment['amount'] . ' ' . $dataPayment['name']],
      ['priceId' => $dataPayment['price_id']],
      ['price' => $dataPayment['total_price']],
      ['user_id' => $dataPayment['user']],
      ['phone' => $dataPayment['phone']],
    ];

    return $dataAttribute;
  }

  public function urlRedirect(array $dataParse)
  {
    $mnoCodeIndosat1 = 51001;
    $mnoCodeIndosat2 = 51021;
    $data['apiKey'] = env("CODA_API_KEY");
    $data['orderId'] = $dataParse['orderId'];
    $data['lang'] = 'id';
    $data['merchant_name'] = env("CODA_MERCHANT_NAME");
    $data['type'] = 1;
    $data['pay_type'] = 1;
    $data['mno_code'] = $mnoCodeIndosat1;
    $data['items'] = [
      "code" => $dataParse['priceId'],
      "name" => $dataParse['name'],
      "price" => $dataParse['price']
    ];
    $data['profile'] = [
      "user_id" => $dataParse['user_id'],
    ];

    $dataResponse = $this->_doRequestToApi($data);

    return json_encode($dataResponse);
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
}
