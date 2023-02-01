<?php

namespace App\Helpers;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;

class Razor
{
    public static function UpdateStatus($request)
    {
        if ($request->paymentStatusCode == 00) {
            $status = 1;
            Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with Razor GOLD Invoice ' . $request->referenceId]);
        } else {

            $status = 2;
            Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with Razor GOLD Invoice ' . $request->referenceId]);
        };

        $trx = Transaction::where('invoice', $request->referenceId)->update([
            'status' => $status
        ]);

        $detail = Transaction::where('invoice', $request->referenceId)->first();

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
            'paid_time' => $request->paymentStatusDate
        ]);

        Log::info('info', ['data' => $request->all()]);
        EventsTransaction::dispatch($request->referenceId);
    }
}
