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
        $Id_GOC = "Esp5373790";
        $Id_Unipin = "bcd3a3a3-4c68-419e-bd79-91d8678faf04";
        $Id_GV = "70";

        $notif_Unipin = url('/api/v1/transaction/notify');

        $data = [];
        $result = [];
        $priceData = [];
        foreach ($payment as $pay) {
            $price = Price::where('payment_id', $pay->payment_id)->where('game_id', $gameId)->orderBy('price', 'asc')->get();
            if ($pay->channel_id == "GV") {

                $p = array(
                    "merchanId" => $Id_GV,

                    "payment_id" => $pay->payment_id,
                    // "category_id" => $pay->category_id,
                    "country_id" => $pay->country_id,
                    "channelId" => $pay->channel_id,
                    "name_channel" => $pay->name_channel,
                    "logo_channel" => url('/image/' . $pay->logo_channel),
                    "url"   => $pay->url,
                    "created_at" => $pay->created_at,
                    "updated_at" => $pay->updated_at
                );
            } else if ($pay->channel_id == "ID_KLIKBCA") {
                $p = array(
                    "guid" => $Id_Unipin,
                    "urlAck" => $notif_Unipin,

                    "payment_id" => $pay->payment_id,
                    // "category_id" => $pay->category_id,
                    "country_id" => $pay->country_id,
                    "channel" => $pay->channel_id,
                    "name_channel" => $pay->name_channel,
                    "logo_channel" => url('/image/' . $pay->logo_channel),
                    "url"   => $pay->url,
                    "created_at" => $pay->created_at,
                    "updated_at" => $pay->updated_at
                );
            } else {
                $p = array(
                    "merchanId" => $Id_GOC,

                    "payment_id" => $pay->payment_id,
                    // "category_id" => $pay->category_id,
                    "country_id" => $pay->country_id,
                    "channelId" => $pay->channel_id,
                    "name_channel" => $pay->name_channel,
                    "logo_channel" => url('/image/' . $pay->logo_channel),
                    "url"   => $pay->url,
                    "created_at" => $pay->created_at,
                    "updated_at" => $pay->updated_at
                );
            }


            $data["payment"] = $p;
            $data["price"] = $price;

            array_push($result, $data);
        };

        return $result;
    }
}
