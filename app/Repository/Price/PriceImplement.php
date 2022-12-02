<?php

namespace App\Repository\Price;

use App\Models\Price;
use Illuminate\Support\Str;


class PriceImplement implements PriceRepository
{
    public function getAll()
    {
        $data = Price::with('game', 'payment', 'pricepoint')->get();
        return $data;
    }

    public function Create($request)
    {
        Price::create([
            'price_id' => Str::uuid(),
            'game_id' => $request['game'],
            'payment_id' => $request['payment'],
            'price_point_id' => $request['ppi'],
            'country_id' => $request['currency'],
            'name' => $request['name'],
            'amount' => $request['amount'],
            'price' => $request['price'],
            'is_active' => 1
        ]);

        return;
    }


    public function getId($id)
    {
        $data = Price::where('price_id', $id)->first();

        return $data;
    }

    public function update($id, $request)
    {
        Price::where('price_id', $id)->update([
            'game_id' => $request['game'],
            'payment_id' => $request['payment'],
            'price_point_id' => $request['ppi'],
            'country_id' => $request['currency'],
            'name' => $request['name'],
            'amount' => $request['amount'],
            'price' => $request['price'],
        ]);

        return;
    }

    public function delete($id)
    {
        Price::where('price_id', $id)->delete();

        return;
    }
}
