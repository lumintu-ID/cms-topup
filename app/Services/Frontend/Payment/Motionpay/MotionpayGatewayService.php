<?php

namespace App\Services\Frontend\Payment\Motionpay;

interface MotionpayGatewayService
{
  public function generateDataParse(array $dataPayment);
  public function generateSignature(string $plainText = null);
  public function getDataToRedirect(array $dataParse);
  public function checkSignature($dataResponse, $vaNumber = null);
  public function saveReference(string $trasnId, string $orderId);
  public function saveReferenceVa($data);
  public function calculateLeftTime($expireDate);
  public function checkInvoice(string $id);
}
