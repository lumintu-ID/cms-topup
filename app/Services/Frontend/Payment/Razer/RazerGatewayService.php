<?php

namespace App\Services\Frontend\Payment\Razer;

interface RazerGatewayService
{
  public function generateDataParse(array $dataPayment);
  public function urlRedirect(array $dataParse);
  public function generateSignature(string $plainText);
  public function checkSignature(array $dataResponse);
  public function saveReference(string $paymentId, string $orderId);
}
