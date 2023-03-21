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
    return $this->_paymentRepository->getAllCategoryPayment();
  }

  public function getAllDataCountry()
  {
    return $this->_generalRepository->getAllDataCountry();
  }

  public function getDataGame(string $slug)
  {
    return $this->_generalRepository->getDataGameBySlug($slug);
  }
}
