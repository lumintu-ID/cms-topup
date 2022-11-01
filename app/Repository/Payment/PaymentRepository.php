<?php

namespace App\Repository\Payment;

interface PaymentRepository
{
    public function getAll();

    public function getId($id);

    public function delete($id);
}
