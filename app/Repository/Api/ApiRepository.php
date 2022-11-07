<?php

namespace App\Repository\Api;

interface ApiRepository
{
    public function getGameList();

    public function gameDetail($id);

    public function priceList($payment, $gameId);
}
