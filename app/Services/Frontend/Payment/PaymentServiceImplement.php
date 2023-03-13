<?php

namespace App\Services\Frontend\Payment;

use App\Repository\Frontend\GeneralRepository;
use App\Repository\Frontend\Payment\PaymentRepository;

class PaymentServiceImplement implements PaymentService
{
  public function __construct(
    private PaymentRepository $_paymentRepository,
    private GeneralRepository $_generalRepository
  ) {
  }

  public function getAllCategoryPayment()
  {
    $data = $this->_paymentRepository->getAllCategoryPayment();
    return $data;
  }

  public function getAllDataCountry()
  {
    $data = $this->_generalRepository->getAllDataCountry();
    return $data;
  }

  public function getDataGame(string $slug)
  {
    $data = $this->_generalRepository->getDataGameBySlug($slug);
    return $data;
  }
}
