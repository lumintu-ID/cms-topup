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
    $codePayment =  $this->_invoiceRepository->getNameCodePayment($dataPayment['code_payment']);

    $result['invoice'] = $dataTransaction->toArray();
    $result['game'] = $this->_invoiceRepository->getGameInfo($dataTransaction->game_id);
    $result['payment'] = $dataPayment;
    $result['payment']['name_payment'] = $codePayment;
    $result['payment']['ppn'] = $this->_invoiceRepository->getAllDataPpn()[0]['ppn'];
    $result['payment']['invoice'] = $dataTransaction->invoice;
    $result['payment']['email'] = $dataTransaction->email;
    $result['attribute'] = $this->_getPaymentAttribute($result['payment'], $result['game']);

    return $result;
  }

  private function _getPaymentAttribute(array $dataPayment = null, array $dataGame = null)
  {
    if(empty($dataPayment)) return 'data is null';

    $urlReturn = route('home');
    
    switch (Str::upper($dataPayment['name_payment'])) {
      case 'GV':
        $merchantId = env('GV_MERCHANT_ID');
        $mercahtKey = env('GV_MERCHANT_KEY');
        $methodAction = 'GET';
        $sign = hash('md5', $merchantId.$dataPayment['price'].$mercahtKey.$dataPayment['invoice']);
        $dataAttribute = [
          ['urlAction' => $dataPayment['url']],
          ['methodAction' => $methodAction],
          ['merchantid' => $merchantId],
          ['custom' => $dataPayment['invoice']],
          ['product' => $dataPayment['amount'].' '.$dataPayment['name']],
          ['amount' => $dataPayment['price']],
          ['custom_redirect' => $urlReturn],
          ['email' => $dataPayment['email']],
          ['signature' => $sign],
        ];
        return json_encode($dataAttribute);
      break;

      case 'UNIPIN':
        $methodAction = 'POST';
        $guid = env('UNIPIN_DEV_GUID');
        $secretKey = env('UNIPIN_DEV_SECRET_KEY');
        $currency = 'IDR';
        $reference =  $dataPayment['invoice'];
        $urlAck = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
        $denominations = $dataPayment['price'].$dataPayment['amount'].' '.$dataPayment['name'];
        $signature = hash('sha256', $guid.$reference.$urlAck.$currency.$denominations.$secretKey);
        $dataParse = [
          'guid' => $guid,
          'reference' => $reference,
          'urlAck' => $urlAck,
          'currency' => $currency,
          'remark' => $dataGame['game_title'],
          'signature' => $signature,
          'denominations' => [
            'amount' => $dataPayment['price'],
            'description' => $dataPayment['amount'].' '.$dataPayment['name']
          ]
        ];
        $dataAttribute = [
          'methodAction' => $methodAction,
          'urlAction' => $dataPayment['url'],
          'dataparse' => $dataParse,
        ];

        return json_encode($dataAttribute);
      break;
        
      case 'GOC':
        $methodAction = 'POST';
        $merchantId = env('GOC_MERCHANT_ID');
        $haskey = env('GOC_HASHKEY');
        $trxDateTime= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('Y-m-d\TH:i:s')."+07";
        $currency = "IDR";
        $sign = hash('sha256', $merchantId.$dataPayment['invoice'].$trxDateTime.$dataPayment['channel_id'].$dataPayment['price'].$currency.$haskey);
        $phone = '08777535648447';
        // $phone = '082119673390';
        $dataAttribute = [
          ['urlAction' => $dataPayment['url']],
          ['methodAction' => $methodAction],
          ['merchantId' => $merchantId],
          ['trxId' => $dataPayment['invoice']],
          ['trxDateTime' => $trxDateTime],
          ['channelId' => $dataPayment['channel_id']],
          ['amount' => $dataPayment['price']],
          ['currency' => $currency],
          ['returnUrl' => $urlReturn],
          ['name' => 'name'],
          ['email' => $dataPayment['email']],
          ['phone' => $phone],
          ['userId' => 'userId'],
          ['sign' => $sign],
        ];
        
        return json_encode($dataAttribute);
      break;
    }
  }
  
}