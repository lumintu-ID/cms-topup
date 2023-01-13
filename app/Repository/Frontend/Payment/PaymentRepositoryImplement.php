<?php

namespace App\Repository\Frontend\Payment;

use App\Models\Category;

class PaymentRepositoryImplement implements PaymentRepository
{
  public function getAllCategoryPayment()
  {
    $data = Category::select(
      'category_id as id',
      'category')
    ->get()->toArray();
    
    return $data;
  }
}