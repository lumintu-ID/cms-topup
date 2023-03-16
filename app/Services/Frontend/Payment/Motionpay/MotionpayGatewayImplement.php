<?php

namespace App\Services\Frontend\Payment\Motionpay;

use App\Repository\Frontend\Payment\MotionpayRepository;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class MotionpayGatewayImplement implements MotionpayGatewayService
{
  private $_dateTime, $_merchantCode, $_secretKey, $_timeLimitVa, $_motionpayRepository, $_statusPending, $_statusSuccess, $_statusFailed, $_urlPayment, $_methodActionPost, $_urlNotify, $_currencyIDR;

  public function __construct(MotionpayRepository $motionpayRepository)
  {
    $this->_methodActionPost = 'POST';
    $this->_urlNotify = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
    $this->_currencyIDR = 'IDR';
    $this->_motionpayRepository = $motionpayRepository;
    $this->_merchantCode = env("MOTIONPAY_MERCHANT_CODE");
    $this->_secretKey = env("MOTIONPAY_SECRET_KEY");
    $this->_urlPayment = env("MOTIONPAY_URL_DEVELOPMENT");
    $this->_timeLimitVa = 60;
    $this->_statusPending = 'Pending';
    $this->_statusSuccess = 'Success';
    $this->_statusFailed = null;
  }

  public function generateDataParse(array $dataPayment)
  {
    try {

      if ($this->checkInvoice($dataPayment['invoice'])) {
        $dataVa = $this->checkInvoice($dataPayment['invoice']);
        $dataVa['leftTime'] = $this->calculateLeftTime($dataVa['expired_time']);

        return $dataVa;
      }

      $response = $this->getDataToRedirect($dataPayment);

      if (!empty($response['va_number'])) {
        if (!$this->checkSignature($response, $response['va_number'])) throw new Exception('Invalid Signature', 403);

        $this->saveReferenceVa($response);
        $response['leftTime'] = $this->calculateLeftTime($response['expired_time']);
        return $response;
      }

      if (!$this->checkSignature($response)) throw new Exception('Invalid Signature', 403);

      $dataAttribute = [
        ['methodAction' => $this->_methodActionPost],
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
  public function getDataToRedirect(array $dataParse)
  {
    try {
      $this->_dateTime = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('YmdHis');
      $merchantCode = $this->_merchantCode;
      $firstName = $dataParse['user'];
      $lastName = $dataParse['user'];
      $email = $dataParse['email'];
      $phone = $dataParse['phone'] ?? null;
      $orderId = $dataParse['invoice'];
      $numberReference = $dataParse['invoice'];
      $amount = (string)$dataParse['total_price'];
      $currency = $this->_currencyIDR;
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
        . $this->_timeLimitVa
        . $this->_urlNotify
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
        'time_limit' => $this->_timeLimitVa,
        'notif_url' => $this->_urlNotify,
        'thanks_url' => $thanksUrl,
        'signature' => $this->generateSignature($plainText)
      ];

      $client = new Client();
      $response = $client->request($this->_methodActionPost, $this->_urlPayment, [
        'headers' => ['Content-type' => 'application/json'],
        'body' => json_encode($payload),
      ]);

      $dataResponse = json_decode($response->getBody()->getContents(), true);

      $this->saveReference($dataResponse['trans_id'], $dataResponse['order_id']);
      return $dataResponse;
    } catch (RequestException $error) {
      echo 'Error message: ' . $error;
    }
  }
  public function checkSignature($dataResponse, $vaNumber = null)
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
  public function saveReference(string $trasnId, string $orderId)
  {
    DB::beginTransaction();
    try {
      if ($this->_motionpayRepository->checkReference($orderId)) return;
      $this->_motionpayRepository->saveReference($trasnId, $orderId);
      DB::commit();
      return;
    } catch (\Throwable $th) {
      DB::rollback();
      abort(500, 'Internal error, please try again');
    }
  }
  public function saveReferenceVa($data)
  {
    DB::beginTransaction();
    try {
      if ($this->_motionpayRepository->checkReferenceVa($data['order_id'])) return;
      $this->_motionpayRepository->saveReferenceVa($data);
      DB::commit();
      return;
    } catch (\Throwable $th) {
      DB::rollback();
      abort(500);
    }
  }
  public function calculateLeftTime($expireDate)
  {
    $expireTime = Carbon::createFromFormat('Y-m-d H:i:s', $expireDate);
    $current =  Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
    $leftTime = Carbon::parse($current)->diffForHumans($expireTime);
    return $leftTime;
  }
  public function checkInvoice(string $id)
  {
    $dataStatusTransaction = $this->_motionpayRepository->getStatusTransaction($id);
    $dataInvoice = $this->_motionpayRepository->getInvoceVa($id);

    if (!empty($dataInvoice['expired_time'])) {
      $now = Carbon::createFromTimeString(Carbon::now());
      $expired_time = Carbon::createFromTimeString($dataInvoice['expired_time']);

      if ($now > $expired_time) {
        $dataInvoice['status_desc'] = $this->_statusFailed;
        return $dataInvoice;
      }

      $dataInvoice['status_desc'] = $this->_statusPending;

      return $dataInvoice;
    }

    if (!empty($dataInvoice['number_va']) && $dataStatusTransaction['status'] == 0) {
      $dataInvoice['status_desc'] = $this->_statusPending;
      return $dataInvoice;
    }

    if (!empty($dataInvoice['number_va']) && $dataStatusTransaction['status'] == 1) {
      $dataInvoice['status_desc'] = $this->_statusSuccess;
      return $dataInvoice;
    }

    return;
  }
}
