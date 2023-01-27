<?php

namespace App\Services\Frontend\Payment;

interface PaymentService
{
  public function getAllCategoryPayment();
  public function getAllDataCountry();
  public function getDataGame(string $slug);
}
