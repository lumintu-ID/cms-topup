<?php

namespace App\Repository\Frontend\Payment\Razer;


interface RazerRepository
{
  public function checkInvoice(string $id);
  public function saveReference(array $data);
}
