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
        // dd($row);
        $price_point_id = 'test';
        $game_id = 'test';
        $country_id = 'test';
        $payment_id = 'test';

        if ($row['price_point_id'] != null) {

            $price_point = PricePoint::select('id', 'price_point')->where('price_point', $row['price_point_id'])->first();
            $price_point_id = $price_point->id;
        };

        // dd($price_point_id);


        if ($row['gameid'] != null) {
            $game = GameList::select('id', 'game_id')->where('game_id', $row['gameid'])->first();
            $game_id = $game->id;
        };



        if ($row['currency'] != null) {
            $currency = Country::select('country_id', 'currency')->where('currency', $row['currency'])->first();
            $country_id = $currency->country_id;
        };



        if ($row['channelid'] != null) {
            $payment = Payment::select('payment_id', 'channel_id')->where('channel_id', $row['channelid'])->first();
            $payment_id = $payment->payment_id;
        };

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
