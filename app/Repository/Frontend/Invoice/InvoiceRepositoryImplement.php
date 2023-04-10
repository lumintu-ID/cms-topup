<?php

namespace App\Repository\Frontend\Invoice;

use App\Models\Category;
use App\Models\Code_payment;
use App\Models\GameList;
use App\Models\Ppn;
use App\Models\Price;
use App\Models\Transaction;

class InvoiceRepositoryImplement implements InvoiceRepository
{
  public function getTransactionById(string $id)
  {
    return Transaction::select(
      'invoice',
      'game_id',
      'id_player',
      'price_id',
      'email',
      'phone',
      'total_price',
      'status',
      'created_at as date'
    )->where('invoice', $id)->first();
  }

  public function getGameInfo(string $id)
  {
    return GameList::select('game_title')
      ->where('id', $id)
      ->first()
      ->toArray();
  }

  public function getDetailPrice($id)
  {
    return Price::join('payments', 'prices.payment_id', '=', 'payments.payment_id')
      ->join('code_payments', 'payments.code_payment', '=', 'code_payments.id')
      ->select(
        'price_id',
        'channel_id',
        'name_channel',
        'price',
        'name',
        'amount',
        'code_payments.code_payment',
        'category_id',
        'url'
      )->where('price_id', $id)->first();
  }

  public function getNameCodePayment(string $id)
  {
    $data = Code_payment::select('code_payment')
      ->where('id', $id)
      ->first()
      ->toArray();
    return $data['code_payment'];
  }

  public function getAllDataPpn()
  {
    return Ppn::select('id_ppn as id', 'ppn')->get()->toArray();
  }

  public function getAllCategoryPayment()
  {
    return Category::select('category_id as id', 'category')->get()->toArray();
  }
}
