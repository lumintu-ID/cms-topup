<?php

namespace App\Services\Frontend\Invoice;

use App\Repository\Frontend\Invoice\InvoiceRepository;
use Illuminate\Support\Str;

class InvoiceServiceImplement implements InvoiceService
{
  private $_invoiceRepository;

  public function __construct(InvoiceRepository $invoiceRepository)
  {
    $this->_invoiceRepository = $invoiceRepository;
  }

  public function getInvoice($id)
  {
    $dataTransaction = $this->_invoiceRepository->getTransactionById($id);
    $dataPayment = $this->_invoiceRepository->getDetailPrice($dataTransaction->price_id)->toArray();

    $result['invoice'] = $dataTransaction->toArray();
    $result['game'] = $this->_invoiceRepository->getGameInfo($dataTransaction->game_id);
    $result['payment'] = $dataPayment;
    $result['payment']['phone'] = null;
    $result['payment']['ppn'] = $this->_invoiceRepository->getAllDataPpn()[0]['ppn'];
    $result['payment']['invoice'] = $dataTransaction->invoice;
    $result['payment']['user'] = $dataTransaction->id_player;
    $result['payment']['email'] = $dataTransaction->email;
    $result['attribute'] = $this->_getPaymentAttribute($result['payment'], $result['game']);

    return $result;
  }

  private function _getPaymentAttribute(array $dataPayment = null, array $dataGame = null)
  {
    if (empty($dataPayment)) return 'data is null';

    $urlReturn = route('home');
    $methodActionPost = "POST";
    $methodActionGet = "GET";
    $trxDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('Y-m-d\TH:i:s');

    $notifUrl = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';

    switch (Str::upper($dataPayment['code_payment'])) {
      case env('GV_CODE_PAYMENT'):
        $merchantId = env('GV_MERCHANT_ID');
        $mercahtKey = env('GV_MERCHANT_KEY');
        $sign = hash('md5', $merchantId . $dataPayment['price'] . $mercahtKey . $dataPayment['invoice']);
        $dataAttribute = [
          ['urlAction' => $dataPayment['url']],
          ['methodAction' => $methodActionGet],
          ['merchantid' => $merchantId],
          ['custom' => $dataPayment['invoice']],
          ['product' => $dataPayment['amount'] . ' ' . $dataPayment['name']],
          ['amount' => $dataPayment['price']],
          ['custom_redirect' => $urlReturn],
          ['email' => $dataPayment['email']],
          ['signature' => $sign],
        ];

        return json_encode($dataAttribute);
        break;

      case env('UNIPIN_CODE_PAYMENT'):
        $guid = env('UNIPIN_DEV_GUID');
        $secretKey = env('UNIPIN_DEV_SECRET_KEY');
        $currency = 'IDR';
        $reference =  $dataPayment['invoice'];
        $urlAck = $notifUrl;
        $denominations = $dataPayment['price'] . $dataPayment['amount'] . ' ' . $dataPayment['name'];
        $signature = hash('sha256', $guid . $reference . $urlAck . $currency . $denominations . $secretKey);
        $dataParse = [
          'guid' => $guid,
          'reference' => $reference,
          'urlAck' => $urlAck,
          'currency' => $currency,
          'remark' => $dataGame['game_title'],
          'signature' => $signature,
          'denominations' => [
            [
              'amount' => $dataPayment['price'],
              'description' => $dataPayment['amount'] . ' ' . $dataPayment['name']
            ]
          ]
        ];
        $dataRedirectTo = [
          'methodAction' => $methodActionGet,
          'idForm' => 'formRedirectUnp',
          'inputElement' => [
            'status',
            'message',
            'url',
            'signature',
          ]
        ];
        $dataAttribute = [
          'methodAction' => $methodActionPost,
          'urlAction' => $dataPayment['url'],
          'dataParse' => $dataParse,
          'dataRedirectTo' => $dataRedirectTo
        ];

        return json_encode($dataAttribute);
        break;

      case env('GOC_CODE_PAYMENT'):
        $merchantId = env('GOC_MERCHANT_ID');
        $haskey = env('GOC_HASHKEY');
        $trxDateTime = substr(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('Y-m-d\TH:i:sP'), 0, -3);
        $currency = "IDR";
        $sign = hash('sha256', $merchantId . $dataPayment['invoice'] . $trxDateTime . $dataPayment['channel_id'] . $dataPayment['price'] . $currency . $haskey);
        // $phone = '08777535648447';
        // $phone = '082119673393';
        $dataAttribute = [
          ['urlAction' => $dataPayment['url']],
          ['methodAction' => $methodActionPost],
          ['merchantId' => $merchantId],
          ['trxId' => $dataPayment['invoice']],
          ['trxDateTime' => $trxDateTime],
          ['channelId' => $dataPayment['channel_id']],
          ['amount' => $dataPayment['price']],
          ['currency' => $currency],
          ['returnUrl' => $urlReturn],
          ['name' => 'name'],
          ['email' => $dataPayment['email']],
          ['phone' => ($dataPayment['phone']) ? $dataPayment['phone'] : null],
          ['userId' => 'userId'],
          ['sign' => $sign],
        ];

        return json_encode($dataAttribute);
        break;

      case env("MOTIONPAY_CODE_PAYMENT"):
        $dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('YmdHis');
        $merchantCode = env("MOTIONPAY_MERCHANT_CODE");
        $firstName = $dataPayment['user'];
        $lastName = $dataPayment['user'];
        $email = $dataPayment['email'];
        $phone = '082119673393';
        $orderId = $dataPayment['invoice'];
        $numberReference = $dataPayment['invoice'];
        $amount = (string)$dataPayment['price'];
        $currency = "IDR";
        $itemDetails = $dataPayment['name'] . $dataPayment['amount'];
        $datetimeRequest = $dateTime;
        $paymentMethod = 'ALL';
        $timeLimit = '60';
        $thanksUrl = route('home');
        $secretKey = env("MOTIONPAY_SECRET_KEY");
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
          . $datetimeRequest
          . $paymentMethod
          . $timeLimit
          . $notifUrl
          . $thanksUrl
          . $secretKey;
        $signature = hash('sha1', md5($plainText));
        $dataParse = [
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
          'datetime_request' => $datetimeRequest,
          'payment_method' => $paymentMethod,
          'time_limit' => $timeLimit,
          'notif_url' => $notifUrl,
          'thanks_url' => $thanksUrl,
          'signature' => $signature,
        ];
        $dataRedirectTo = [
          'methodAction' => $methodActionPost,
          'url' => route('payment.test'),
          'idForm' => 'formRedirectMp',
          'inputElement' => [
            'trans_id',
            'merchant_code',
            'order_id',
            'signature',
          ]
        ];
        $dataAttribute = [
          'methodAction' => $methodActionPost,
          'urlAction' => env("MOTIONPAY_URL"),
          'dataParse' => $dataParse,
          'dataRedirectTo' => $dataRedirectTo
        ];

        return json_encode($dataAttribute);
        break;

      case env("RAZE_NAME_PAYMENT"):
        $dataAttribute = "Razer payment";

        return json_encode($dataAttribute);
        break;
    }
  }
}
