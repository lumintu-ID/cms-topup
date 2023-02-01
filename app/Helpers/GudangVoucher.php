<?php

namespace App\Helpers;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;

class GudangVoucher
{
    public static function UpdateStatus($request)
    {
        $dataXML = $request->data;
        $xmlObject = simplexml_load_string($dataXML);

        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);

        Log::info('info', ['data' => $phpArray]);
        EventsTransaction::dispatch($phpArray['custom']);

        if ($phpArray['status'] == "SUCCESS") {
            $status = 1;
            Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GV Invoice ' . $phpArray['custom']]);
        } else {
            $status = 2;
            Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GV Invoice ' . $phpArray['custom']]);
        };

        $trx = Transaction::where('invoice', $phpArray['custom'])->update([
            'status' => $status
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
            'paid_time' => date('d-m-Y H:i', $phpArray['payment_time']),
        ]);
    }

    public static function Check($request)
    {
        $Merchantkey = env('GV_MERCHANT_KEY');
        $Merchantid = env('GV_MERCHANT_ID');
        $custom = $request->invoice;
        $signature = md5($Merchantkey . $Merchantid . $custom);



        $response = Http::get('https://www.gudangvoucher.com/cpayment.php?merchantid=' . $Merchantid . '&custom=' . $custom . 'signature=' . $signature . '');

        return response()->json([
            'code' => 200,
            'status' => 'SUCCESS',
            'data' => $response->body(),
        ], 200);
    }
}
