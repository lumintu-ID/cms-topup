<?php

namespace App\Repository\Frontend\Payment;

use App\Models\Reference;
use App\Models\Transaction;
use App\Models\VirtualAccount;

class MotionpayRepository
{
  public function checkReference(string $id)
  {
    return Reference::where('invoice', $id)->first();
  }

  public function checkReferenceVa(string $id)
  {
    return VirtualAccount::where('invoice', $id)->first();
  }

  public function saveReference(string $trasnId, string $orderId)
  {
    Reference::create(['invoice' => $orderId, 'reference' => $trasnId]);
    return;
  }
  public function saveReferenceVa(array $data)
  {
    VirtualAccount::create(['invoice' => $data['order_id'], 'VA' => $data['va_number'], 'expired_time' => $data['expired_time']]);
    return;
  }

  public function getInvoceVa(string $id)
  {
    return VirtualAccount::select(
      'VA as va_number',
      'expired_time'
    )->where('invoice', $id)->first();
  }

  public function getStatusTransaction(string $id)
  {
    return Transaction::select(
      'status'
    )->where('invoice', $id)->first();
  }
}
