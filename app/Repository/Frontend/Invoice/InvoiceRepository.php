<?php

namespace App\Repository\Frontend\Invoice;

interface InvoiceRepository
{
  public function getTransactionById(string $invoiceId);
  public function getGameInfo(string $gameId);
  public function getDetailPrice(string $priceId);
}