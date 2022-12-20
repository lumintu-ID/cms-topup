<?php

namespace App\Services\Frontend\Invoice;

use App\Repository\Frontend\Invoice\InvoiceRepository;
use Illuminate\Support\Str;

class InvoiceServiceImplement implements InvoiceService
{
  private $invoiceRepository;

  public function __construct(InvoiceRepository $invoiceRepository)
  {
    $this->invoiceRepository = $invoiceRepository;
  }
  
  public function getInvoice($id) 
  {
    $dataTransaction = $this->invoiceRepository->getTransactionById($id);
    $result['invoice'] = $dataTransaction->toArray();
    $result['game'] = $this->invoiceRepository->getGameInfo($dataTransaction->game_id);
    $result['payment'] = $this->invoiceRepository->getDetailPrice($dataTransaction->price_id)->toArray(); 
    $result['payment']['invoice'] = $dataTransaction->invoice;
    $result['payment']['email'] = $dataTransaction->email;
    $result['attribute'] = $this->getPaymentAttribute($result['payment']);
    
    return $result;
  }

  private function getPaymentAttribute(array $dataPayment = null)
  {
    if(empty($dataPayment)) return 'data is null';
    
    switch (Str::upper($dataPayment['channel_id'])) {
      case 'GV':
        $merchantId = "1138";
        $mercahtKey = '947f512d9b86b517a0070d5a';
        $methodAction = 'GET';
        $sign = hash('md5', $merchantId.$dataPayment['price'].$mercahtKey.$dataPayment['invoice']);
        $dataAttribute = [
          ['urlAction' => $dataPayment['url']],
          ['methodAction' => $methodAction],
          ['merchantid' => $merchantId],
          ['custom' => $dataPayment['invoice']],
          ['product' => $dataPayment['amount'].' '.$dataPayment['name']],
          ['amount' => $dataPayment['price']],
          ['custom_redirect' => 'http://127.0.0.1:8000/'],
          ['email' => $dataPayment['email']],
          ['signature' => $sign],
        ];
        return json_encode($dataAttribute);
      break;
        
      default:
        $methodAction = 'POST';
        $merchantId = "Esp5373790";
        $haskey = 'jqji815m748z0ql560982426ca0j70qk02411d2no6u94qgdf58js2jn596s99si';
        $trxDateTime= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('Y-m-d\TH:i:s')."+07";
        $currency = "IDR";
        $sign = hash('sha256', $merchantId.$dataPayment['invoice'].$trxDateTime.$dataPayment['channel_id'].$dataPayment['price'].$currency.$haskey);
        $phone = '0865765898999';
        $dataAttribute = [
          ['urlAction' => $dataPayment['url']],
          ['methodAction' => $methodAction],
          ['merchantId' => $merchantId],
          ['trxId' => $dataPayment['invoice']],
          ['trxDateTime' => $trxDateTime],
          ['channelId' => $dataPayment['channel_id']],
          ['amount' => $dataPayment['price']],
          ['currency' => $currency],
          ['returnUrl' => 'http://127.0.0.1:8000/'],
          ['name' => 'name'],
          ['email' => $dataPayment['email']],
          ['phone' => (int)$phone],
          ['userId' => 'userId'],
          ['sign' => $sign],
        ];
        
        return json_encode($dataAttribute);
      break;
    }
  }
  
}