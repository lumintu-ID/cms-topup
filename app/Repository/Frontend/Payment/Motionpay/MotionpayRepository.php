<?php

namespace App\Repository\Frontend\Payment\Motionpay;

interface MotionpayRepository
{
  public function checkReference(string $id);
  public function checkReferenceVa(string $id);
  public function saveReference(string $trasnId, string $orderId);
  public function saveReferenceVa(array $data);
  public function getInvoceVa(string $id);
  public function getStatusTransaction(string $id);
}
