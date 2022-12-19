<?php

namespace App\Services\Frontend\Invoice;

use App\Models\GameList;
use App\Models\Payment;
use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;

class InvoiceServiceImplement implements InvoiceService
{
  public function getInvoice($id) {
    $dataPayment = $this->getPayment($this->getTransaction($id));

    // dd($dataPayment);
    // $result = [];
    // $dataInvoice = [];
    // $dataInvoice["invoice"] = $dataTransaction;
    // $dataInvoice["game"] = $dataGame;
    // $dataInvoice["payment"] = $dataPayment;
    // $dataInvoice["price"] = $dataPrice;

    // array_push($result, $dataInvoice);
    
    // return $result;
    return $dataPayment;
  }

  private function getTransaction($id)
  {
    $dataPrice = Transaction::select('invoice', 'game_id', 'id_player', 'method_payment', 'price_id', 'email', 'total_price')->where('invoice', $id)->first();
    return $dataPrice;
  }

  // private function getGameInfo($id)
  // {
  //   $dataGame = GameList::select('id', 'game_id', 'game_title')->where('id', $id)->first();
  //   return $dataGame;
  // }

  // private function getPrice($id)
  // {
  //   $dataPrice = Price::select('price_id', 'price_point_id', 'price', 'amount', 'name')->where('price_id', $id)->first();
  //   return $dataPrice;
  // }

  private function getPayment($dataTransaction)
  {
    $dataPayment = Price::join('payments', 'prices.payment_id', '=', 'payments.payment_id')
    ->select('channel_id', 'price', 'name', 'amount', 'category_id', 'url')
    ->where('price_id', $dataTransaction->price_id)->first();
    
    $result['payment'] = $dataPayment->toArray(['amount']);
    $result['payment']['invoice'] = $dataTransaction->invoice;
    $result['payment']['email'] = $dataTransaction->email;
   
    return $this->getPaymentAttribute($result['payment']);
  }


  private function getPaymentAttribute(array $dataPayment = null)
  {
    if(empty($dataPayment)) return 'data is null';
    
    switch (Str::upper($dataPayment['channel_id'])) {
      case 'GV':
        $merchantId = "1138";
        $mercahtKey = '947f512d9b86b517a0070d5a';
        $sign = hash('md5', $merchantId.$dataPayment['amount'].$mercahtKey.$dataPayment['invoice']);
        $dataAttribute = [
          ['methodAction' => 'GET'],
          ['merchantid' => $merchantId],
          ['custom' => $dataPayment['invoice']],
          ['product' => '320 Stone'],
          ['amount' => $dataPayment['amount']],
          ['custom_redirect' => 'http://127.0.0.1:8000/'],
          ['email' => $dataPayment['email']],
          ['signature' => $sign],
        ];
        return $dataAttribute;
      break;
        
      default:
        $merchantId = "Esp5373790";
        $haskey = 'jqji815m748z0ql560982426ca0j70qk02411d2no6u94qgdf58js2jn596s99si';
        $trxDateTime= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('Y-m-d\TH:i:s')."+07";
        $currency = "IDR";
        $sign = hash('sha256', $merchantId.$dataPayment['invoice'].$trxDateTime.$dataPayment['channel_id'].$dataPayment['amount'].$currency.$haskey);
        $test = array('test');
        $dataAttribute = [];
        // array_push($dataAttribute, $test);
        // dd($dataAttribute);
        $dataAttribute = [
          ['methodAction' => 'POST'],
          ['merchant' => $merchantId],
          ['trxId' => $dataPayment['invoice']],
          ['trxDateTime' => $trxDateTime],
          ['channelId' => $dataPayment['channel_id']],
          ['amount' => $dataPayment['price']],
          ['currency' => $currency],
          ['url' => 'http://127.0.0.1:8000/'],
          ['name' => 'name'],
          ['email' => $dataPayment['email']],
          ['phone' => 'phone'],
          ['klikbcaId' => 'klikbcaId'],
          ['userId' => 'userId'],
          ['sign' => $sign],
        ];
        // $dataAttribute = [
        //   ['methodAction' => 'POST'],
        //   ['merchant' => $merchantId],
        //   ['trxId' => $dataPayment['invoice']],
        //   ['trxDateTime' => $trxDateTime],
        //   ['channelId' => $dataPayment['channel_id']],
        //   ['amount' => $dataPayment['price']],
        //   ['currency' => $currency],
        //   ['url' => 'http://127.0.0.1:8000/'],
        //   ['name' => 'name'],
        //   ['email' => $dataPayment['email']],
        //   ['phone' => 'phone'],
        //   ['klikbcaId' => 'klikbcaId'],
        //   ['userId' => 'userId'],
        //   ['sign' => $sign],
        // ];
        // dd($dataAttribute);

        return $dataAttribute;
      break;
    }
  }
  
}