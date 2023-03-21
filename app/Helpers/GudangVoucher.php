<?php

namespace App\Helpers;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GudangVoucher
{
    public static function UpdateStatus($request)
    {



        $dataXML = $request->data;
        $xmlObject = simplexml_load_string($dataXML);

        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);

        Log::info('info', ['data' => $phpArray]);

        if ($phpArray['status'] == "SUCCESS") {
            $status = 1;
            Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GV Invoice ' . $phpArray['custom']]);
        } else {
            $status = 2;
            Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GV Invoice ' . $phpArray['custom']]);
        };
        $trx = Transaction::where('invoice', $phpArray['custom'])->update([
            'status' => $status,
            'paid_time' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $detail = Transaction::where('invoice', $phpArray['custom'])->first();

        $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

        TransactionDetail::create([
            'detail_id' => Str::uuid(),
            'invoice_id' => $detail->invoice,
            'player_id' => $detail->id_Player,
            'game_id' => $detail->game_id,
            'ppi' => $price->pricepoint->price_point,
            'method' => $price->payment->name_channel,
            'amount' => $price->amount . ' ' . $price->name,
            'total_paid' => $detail->total_price,
            'paid_time' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public static function Check($request)
    {
        $Merchantkey = env('GV_MERCHANT_KEY');
        $Merchantid = env('GV_MERCHANT_ID');
        $custom = $request->invoice;
        $signature = md5($Merchantkey . $Merchantid . $custom);



        $response = Http::get('https://www.gudangvoucher.com/cpayment.php?merchantid=' . $Merchantid . '&custom=' . $custom . '&signature=' . $signature);


        $data = $response->body();

        $xml = '<trans_doc>
                    <merchant_id>70</merchant_id>
                    <reference>GV35519829368965</reference>
                    <custom>bf9420a484471d8e119ceb3651d339cf</custom>
                    <amount currency="IDR" nominal="150000"/>
                    <signature>369e14b0836303071d873285c911913a</signature>
                    <status>SUCCESS</status>
                </trans_doc>';

        // Mengurai data XML menjadi objek
        $data = simplexml_load_string($data);

        if ($data->status == null) {
            // Menampilkan data

            echo "Status Gudang Voucher <br>";
            echo "<hr>";
            echo "Merchant ID: " . $data->merchant_id . "<br>";
            echo "Reference: " . $data->reference . "<br>";
            echo "Custom: " . $data->custom . "<br>";
            echo "Amount: " . $data->amount->attributes()->nominal . " " . $data->amount->attributes()->currency . "<br>";
            echo "Signature: " . $data->signature . "<br>";
            echo "Status: " . $data->status . "<br>";

            echo "<hr>";
            echo "---------------------Detail Transaction-------------------- <br>";
        } else {
            echo "transaction not found";
        }
    }
}
