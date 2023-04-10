<?php

namespace App\Services\Frontend\Payment\Coda;

interface CodaGatewayService
{
  public function generateDataParse(array $dataPayment);
  public function urlRedirect(array $dataParse);
}
