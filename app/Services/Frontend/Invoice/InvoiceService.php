<?php

namespace App\Services\Frontend\Invoice;

interface InvoiceService
{
  public function getInvoice($id);
  public function redirectToPayment(string $codePayment, array $dataParse);
  public function confrimInfo(array $dataRequest);
}
