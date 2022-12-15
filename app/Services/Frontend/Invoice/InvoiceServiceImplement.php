<?php

namespace App\Services\Frontend\Invoice;

use App\Models\Payment;
use App\Models\Price;
use App\Models\Transaction;

class InvoiceServiceImplement implements InvoiceService
{
  public function getInvoice($id) {
    $dataTransaction = $this->getTransaction($id);
    $dataPrice = $this->getPrice($dataTransaction->price_id);
    $dataPayment = $this->getPayment($dataTransaction->method_payment);
    // dd($dataTransaction);
    
    $result = [];
    $dataInvoice = [];
    
    $dataInvoice["invoice"] = $dataTransaction;
    $dataInvoice["payment"] = $dataPayment;
    $dataInvoice["price"] = $dataPrice;

    array_push($result, $dataInvoice);
    
    return $result;
  }

  private function getTransaction($id)
  {
    $dataPrice = Transaction::select('invoice', 'game_id', 'id_player', 'method_payment', 'price_id', 'email', 'total_price')->where('invoice', $id)->first();
    return $dataPrice;
  }

  private function getPrice($id)
  {
    $dataPrice = Price::select('price_id', 'price_point_id', 'price')->where('price_id', $id)->first();
    return $dataPrice;
  }

  private function getPayment($id)
  {
    $dataPayment = Payment::select('payment_id', 'name_channel', 'url', 'channel_id', 'category_id')->where('payment_id', $id)->first();
    return $dataPayment;
  }
  
}