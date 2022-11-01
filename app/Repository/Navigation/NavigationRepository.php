<?php

namespace App\Repository\Navigation;

interface NavigationRepository
{
    public function getAll();

    public function create($request);

    public function getId($id);

    public function update($id, $request);

    public function delete($id);



    public function updateStatus($id, $request);
}
