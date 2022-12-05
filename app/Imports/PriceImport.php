<?php

namespace App\Imports;

use App\Models\Price;
use App\Models\Country;
use App\Models\Payment;
use App\Models\GameList;
use App\Models\PricePoint;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PriceImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $price_point_id = 'test';
        $game_id = 'test';
        $country_id = 'test';
        $payment_id = 'test';

        if ($row['price_point_id'] != null) {

            $price_point = PricePoint::where('price_point', $row['price_point_id'])->get();
            if (count($price_point) > 0) {
                $price_point_id = $price_point[0]->id;
            };
        };

        // dd($price_point_id);


        if ($row['gameid'] != null) {
            $game = GameList::where('game_id', $row['gameid'])->get();
            if (count($game) > 0) {
                $game_id = $game[0]->id;
            };
        };
        // dd($game_id);


        if ($row['currency'] != null) {
            $currency = Country::where('currency', $row['currency'])->get();
            if (count($currency) > 0) {
                $country_id = $currency[0]->country_id;
            };
        };
        // dd($country_id);


        if ($row['channelid'] != null) {
            $payment = Payment::where('channel_id', $row['channelid'])->get();
            if (count($payment) > 0) {
                $payment_id = $payment[0]->payment_id;
            };
        };
        // dd($payment_id);

        if ($game_id != null && $payment_id != null && $price_point_id !=  null && $country_id != null && $row['name'] != null && $row['amount'] != null && $row['price'] != null) {
            return new Price([
                'price_id' => Str::uuid(),
                'game_id' => $game_id,
                'payment_id' => $payment_id,
                'price_point_id' => $price_point_id,
                'country_id' => $country_id,
                'name' => $row['name'],
                'amount' => $row['amount'],
                'price' => $row['price'],
                'is_active' => 1
            ]);
        };
    }
}
