<?php

namespace App\Services\Frontend\Payment\Unipin;

interface UnipinGatewayService
{
  public function generateDataParse(array $dataPayment, array $dataGame = null);
  public function generateSignature(string $plainText = null);
  public function urlRedirect(array $dataParse);
  public function checkSignature($dataResponse);
}
