<?php

namespace App\Repository\Category;

interface CategoryRepository
{
    public function getAll();

    public function create($request);

    public function getId($id);

    public function update($id, $request);

    public function delete($id);
}
