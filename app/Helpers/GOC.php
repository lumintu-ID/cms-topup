<?php

namespace App\Helpers;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Goc
{
    public static function UpdateStatus($request)
    {
        if ($request->status == 100) {
            $status = 1;
            Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GOC Invoice ' . $request->trxId]);
        } else {

            $status = 2;
            Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GOC Invoice ' . $request->trxId]);
        };

        $trx = Transaction::where('invoice', $request->trxId)->update([
            'status' => $status
        ]);

        $detail = Transaction::where('invoice', $request->trxId)->first();

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
            'paid_time' => $request->paidDate,
        ]);
    }

    public static function Check($request)
    {
        $hashKey = env('GOC_HASHKEY');
        $Merchantid = env('GOC_MERCHANT_ID');
        $trxId = $request->invoice;

        $signature = hash('sha256', $Merchantid . $trxId . $hashKey);

        $response = Http::asForm()->post('https://pay.goc.id/inquiry/', [
            'merchantId' => $Merchantid,
            'trxId' => $trxId,
            'sign' => $signature
        ]);

        var_dump($response->body());
        // return response()->json([
        //     'code' => 200,
        //     'status' => 'SUCCESS',
        //     'data' => $response->body(),
        // ], 200);
    }
}
