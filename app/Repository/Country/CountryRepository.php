<?php

namespace App\Repository\Country;

interface CountryRepository
{
    public function getAll();

    public function create($request);

    public function getId($id);

    public function update($id, $request);

    public function delete($id);
}
