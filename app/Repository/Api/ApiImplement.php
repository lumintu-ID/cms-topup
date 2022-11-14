<?php

namespace App\Repository\Api;


use App\Models\Price;
use App\Models\GameList;
use App\Repository\Api\ApiRepository;

class ApiImplement implements ApiRepository
{
    public function getGameList($limit)
    {

        if ($limit == null) {
            $limit = 5;
        };

        $data =  GameList::limit($limit)->get();

        return $data;
    }

    public function gameDetail($slug)
    {
        $game = GameList::where('slug_game', $slug)->first();

        return $game;
    }



    public function priceList($payment, $gameId)
    {

        $result = [];
        foreach ($payment as $pay) {
            $price = Price::where('payment_id', $pay->payment_id)->where('game_id', $gameId)->orderBy('price', 'asc')->get();

            $p = array(
                "payment_id" => $pay->payment_id,
                "category_id" => $pay->category_id,
                "country_id" => $pay->country_id,
                "channel_id" => $pay->channel_id,
                "name_channel" => $pay->name_channel,
                "logo_channel" => url('/image/' . $pay->logo_channel),
                "created_at" => $pay->created_at,
                "updated_at" => $pay->updated_at
            );

            $data = [
                'Payment' => $p,
                'Price' => $price
            ];

            array_push($result, $data);
        };

        return $result;
    }
}
