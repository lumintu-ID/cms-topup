<?php

namespace App\Services\Frontend\Invoice;

interface InvoiceService
{
  public function getInvoice(string $id);
  public function redirectToPayment(string $codePayment, array $dataParse);
  public function confrimInfo(array $dataRequest);
}
