<?php

namespace App\Services\Frontend\Payment;

use App\Models\Reference;
use App\Models\VirtualAccount;
use Exception;
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
    try {
      $response = $this->_getDataToRedirect($dataPayment);

      if (!empty($response['va_number'])) {
        if (!$this->_checkSignature($response, $response['va_number'])) throw new Exception('Invalid Signature', 403);

        $this->_saveReferenceVa($response);
        return $response;
      }

      if (!$this->_checkSignature($response)) throw new Exception('Invalid Signature', 403);

      $dataAttribute = [
        ['methodAction' => $this->methodActionPost],
        ['urlAction' => $response['frontend_url']],
        ['trans_id' => $response['trans_id']],
        ['merchant_code' => $response['merchant_code']],
        ['order_id' => $response['order_id']],
        ['signature' => $response['signature']],
      ];

      return json_encode($dataAttribute);
    } catch (\Exception $error) {
      abort($error->getCode(), $error->getMessage());
    }
  }

  public function generateSignature(string $plainText = null)
  {
    $signature = hash('sha1', md5($plainText));
    return $signature;
  }

  private function _getDataToRedirect(array $dataParse)
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

      $this->_saveReference($dataResponse['trans_id'], $dataResponse['order_id']);
      return $dataResponse;
    } catch (RequestException $error) {
      echo 'Error message: ' . $error;
    }
  }

  private function _checkSignature($dataResponse, $vaNumber = null)
  {
    $plainText = null;

    if (empty($vaNumber)) {
      $plainText = $dataResponse['trans_id']
        . $dataResponse['merchant_code']
        . $dataResponse['order_id']
        . $dataResponse['no_reference']
        . $dataResponse['amount']
        . $dataResponse['frontend_url'];
    } else {
      $plainText = $dataResponse['trans_id']
        . $dataResponse['merchant_code']
        . $dataResponse['order_id']
        . $dataResponse['no_reference']
        . $dataResponse['amount']
        . $dataResponse['frontend_url']
        . $dataResponse['fm_refnum']
        . $dataResponse['payment_method']
        . $dataResponse['va_number']
        . $dataResponse['expired_time']
        . $dataResponse['status_code']
        . $dataResponse['status_desc'];
    }

    $signatureMerchat = $this->generateSignature($plainText . $this->_secretKey);

    if ($dataResponse['signature'] == $signatureMerchat) return true;

    return false;
  }

  private function _saveReference(string $trasnId, string $orderId)
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
      abort(500, 'Internal error, please try again');
    }
  }

  private function _saveReferenceVa($data)
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
      abort(500);
    }
  }
}
