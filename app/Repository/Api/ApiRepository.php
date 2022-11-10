<?php

namespace App\Repository\Api;

interface ApiRepository
{
    public function getGameList($limit);

    public function gameDetail($id);

    public function priceList($payment, $gameId);
}
