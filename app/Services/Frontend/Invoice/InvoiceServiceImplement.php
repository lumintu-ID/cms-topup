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
    $dataPayment = $this->invoiceRepository->getDetailPrice($dataTransaction->price_id)->toArray();
    $codePayment =  $this->invoiceRepository->getNameCodePayment($dataPayment['code_payment']);

    $result['invoice'] = $dataTransaction->toArray();
    $result['game'] = $this->invoiceRepository->getGameInfo($dataTransaction->game_id);
    $result['payment'] = $dataPayment;
    $result['payment']['name_payment'] = $codePayment;
    $result['payment']['invoice'] = $dataTransaction->invoice;
    $result['payment']['email'] = $dataTransaction->email;
    $result['attribute'] = $this->getPaymentAttribute($result['payment'], $result['game']);

    return $result;
  }

  private function getPaymentAttribute(array $dataPayment = null, array $dataGame = null)
  {
    if(empty($dataPayment)) return 'data is null';

    $urlReturn = route('home');
    
    switch (Str::upper($dataPayment['name_payment'])) {
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
          ['custom_redirect' => $urlReturn],
          ['email' => $dataPayment['email']],
          ['signature' => $sign],
        ];
        return json_encode($dataAttribute);
      break;

      case 'UNIPIN':
        $methodAction = 'POST';
        $guid = '9b42a14d-a986-40a9-b4cc-354be6aea6db';
        $secretKey = 'w56kbwxuxh3heka3';
        $currency = 'IDR';
        $reference =  $dataPayment['invoice'];
        $urlAck = 'https://esi-paymandashboard.azurewebsites.net/api/v1/transaction/notify';
        $denominations = json_encode($dataPayment['amount'].' '.$dataPayment['name']);
        $signature = hash('sha256', $guid.$reference.$urlAck.$currency.$denominations.$secretKey);
        $dataAttribute = [
          ['urlAction' => $dataPayment['url']],
          ['methodAction' => $methodAction],
          ['guid' => $guid],
          ['reference' => $reference],
          ['urlAck' => $urlAck],
          ['urlReturn' => $urlReturn],
          ['denominations[]' => json_encode([
              'amount' => strval($dataPayment['amount']),
              'description' => $dataPayment['name']
            ])
          ],
          ['currency' => $currency],
          ['channel' => $dataPayment['channel_id']],
          ['remark' => $dataGame['game_title']],
          ['signature' => $signature],
        ];

        return json_encode($dataAttribute);
      break;
        
      case 'GOC':
        $methodAction = 'POST';
        $merchantId = "Esp5373790";
        $haskey = 'jqji815m748z0ql560982426ca0j70qk02411d2no6u94qgdf58js2jn596s99si';
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