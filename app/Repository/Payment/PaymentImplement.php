<?php

namespace App\Repository\Payment;

use App\Models\Payment;
use App\Repository\Payment\PaymentRepository;

class PaymentImplement implements PaymentRepository
{
    public function getAll()
    {
        $data = Payment::with('category', 'country')->orderBy('category_id')->get();

        return $data;
    }

    public function getId($id)
    {
        $data = Payment::where('payment_id', $id)->first();

        return $data;
    }

    public function delete($id)
    {
        $data = Payment::where('payment_id', $id)->delete();

        return $data;
    }
}
