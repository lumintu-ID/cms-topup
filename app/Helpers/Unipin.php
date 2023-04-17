<?php

namespace App\Helpers;

use App\Models\Price;
use GuzzleHttp\Client;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class Unipin
{
    public static function UpdateStatus($request)
    {
        DB::beginTransaction();
        try {
            if ($request['transaction']['status'] == 0) {
                $status = 1;
                Log::info('Success Transaction Paid Unipin', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GOC Invoice ' . $request['transaction']['reference']]);
            } else {

                $status = 2;
                Log::info('Cancel Transaction Paid Unipin', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GOC Invoice ' . $request['transaction']['reference']]);
            };

            $trx = Transaction::where('invoice', $request['transaction']['reference'])->update([
                'status' => $status,
                'paid_time' => date('d-m-Y H:i', $request['transaction']['time'])
            ]);

            $detail = Transaction::where('invoice', $request['transaction']['reference'])->first();

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
                'paid_time' => date('d-m-Y H:i', $request['transaction']['time'])
            ]);

            DB::commit();

            return \response()->json([
                'status' => $request['transaction']['status'],
                'message' => 'Reload Successful',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error Notify TopUp Motion Pay', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Notify TopUp Transaction']);

            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public static function Check($request)
    {
        $guid = env('UNIPIN_DEV_GUID');
        $secret = env('UNIPIN_DEV_SECRET_KEY');
        $url = 'https://dev.unipin.com/api/unibox/inquiry';
        $trxId = $request->invoice;

        $signature = hash('sha256', $guid . $trxId . $secret);

        $data = [
            'guid' => $guid,
            'reference' => $trxId,
            'signature' => $signature
        ];

        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($data),
        ]);


        dd($response->getBody()->getContents());

        // return response()->json([
        //     'code' => 200,
        //     'status' => 'SUCCESS',
        //     'data' => json_decode($response->getBody()->getContents()),
        // ], 200);
    }
}
