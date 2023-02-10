<?php

namespace App\Services\Frontend\Payment;

use App\Models\Reference;
use App\Models\VirtualAccount;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class MotionpayGatewayService extends PaymentGatewayService
{
  private $_dateTime, $_merchantCode, $_secretKey, $_timeLimit;

  public function __construct()
  {
    $this->_timeLimit = "60";
    $this->_merchantCode = env("MOTIONPAY_MERCHANT_CODE");
    $this->_secretKey = env("MOTIONPAY_SECRET_KEY");
    $this->urlPayment = env("MOTIONPAY_URL_DEVELOPMENT");
  }

  public function generateDataParse(array $dataPayment)
  {

    $response = $this->getDataToRedirect($dataPayment);

    if (!empty($response['va_number'])) {
      $this->saveReferenceVa($response);
      return $response;
    }

    $dataAttribute = [
      ['methodAction' => $this->methodActionPost],
      ['urlAction' => $response['frontend_url']],
      ['trans_id' => $response['trans_id']],
      ['merchant_code' => $response['merchant_code']],
      ['order_id' => $response['order_id']],
      ['signature' => $response['signature']],
    ];

    return json_encode($dataAttribute);
  }

  private function getDataToRedirect(array $dataParse)
  {
    try {
      $this->_dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('YmdHis');
      $merchantCode = $this->_merchantCode;
      $firstName = $dataParse['user'];
      $lastName = $dataParse['user'];
      $email = $dataParse['email'];
      $phone = $dataParse['phone'] ?? null;
      $orderId = $dataParse['invoice'];
      $numberReference = $dataParse['invoice'];
      $amount = (string)$dataParse['total_price'];
      $currency = $this->currencyIDR;
      $itemDetails =  $dataParse['amount'] . ' ' . $dataParse['name'];
      $paymentMethod = $dataParse['channel_id'] ?? 'ALL';
      $thanksUrl = route('payment.confirmation.info');
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

      $client = new Client();
      $response = $client->request($this->methodActionPost, $this->urlPayment, [
        'headers' => ['Content-type' => 'application/json'],
        'body' => json_encode($payload),
      ]);

      $dataResponse = json_decode($response->getBody()->getContents(), true);

      return $dataResponse;
      $this->saveReference($dataResponse['trans_id'], $dataResponse['order_id']);
    } catch (RequestException $error) {
      echo 'Error message: ' . $error;
    }
  }

  public function generateSignature(string $plainText)
  {
    $signature = hash('sha1', md5($plainText));
    return $signature;
  }

  private function saveReference(string $trasnId, string $orderId)
  {
    DB::beginTransaction();
    try {
      $checkInvoice = Reference::where('invoice', $orderId)->first();
      if ($checkInvoice) return;
      Reference::create(['invoice' => $orderId, 'reference' => $trasnId]);
      DB::commit();
      return;
    } catch (\Throwable $th) {
      DB::rollback();
      echo 'Internal error, please try again';
    }
  }

  private function saveReferenceVa($data)
  {
    DB::beginTransaction();
    try {
      $checkInvoice = VirtualAccount::where('invoice', $data['order_id'])->first();
      if ($checkInvoice) return;
      VirtualAccount::create(['invoice' => $data['order_id'], 'VA' => $data['va_number'], 'expired_time' => $data['expired_time']]);
      DB::commit();
      return;
    } catch (\Throwable $th) {
      DB::rollback();
      dd($th);
    }
  }
}
