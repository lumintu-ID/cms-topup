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
        $data = [];
        $result = [];
        $priceData = [];
        foreach ($payment as $pay) {
            $price = Price::select('price_id', 'payment_id', 'game_id', 'name', 'amount', 'price')->where('payment_id', $pay->payment_id)->where('game_id', $gameId)->orderBy('price', 'asc')->get();



            $p = array(
                "code_pay" => $pay->code_pay->code_payment,
                "category_id" => $pay->category->category_id,
                "category_name" => $pay->category->category,
                "payment_id" => $pay->payment_id,
                "country_id" => $pay->country_id,
                "channelId" => $pay->channel_id,
                "name_channel" => $pay->name_channel,
                "logo_channel" => url('/image/' . $pay->logo_channel),
                "url" => $pay->url,
                "phone_required" => ($pay->channel_id == "94" || $pay->channel_id == "SPIN") ? true : false,
            );

            $data["payment"] = $p;
            $data["price"] = $price;

            array_push($result, $data);
        };

        return $result;
    }
}
