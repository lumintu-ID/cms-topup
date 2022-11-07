<?php

namespace App\Repository\Price;

interface PriceRepository
{
    public function getAll();

    public function Create($request);

    public function getId($id);

    public function update($id, $request);

    public function delete($id);
}
