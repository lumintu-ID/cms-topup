<?php

namespace App\Helpers;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Goc
{
    public static function UpdateStatus($request)
    {
        DB::beginTransaction();
        try {
            if ($request->status == 100) {
                $status = 1;
                Log::info('Success Transaction Paid GOC', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GOC Invoice ' . $request->trxId]);
            } else {

                $status = 2;
                Log::info('Cancel Transaction Paid GOC', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GOC Invoice ' . $request->trxId]);
            };

            $trx = Transaction::where('invoice', $request->trxId)->update([
                'status' => $status,
                'paid_time' => $request->paidDate,
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

            DB::commit();

            return "OK";
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error Notify TopUp Transaction GOC', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Notify TopUp Transaction']);

            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
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

        $result = $response->body();


        $json = '{
            "inquiryStatus":100,
            "merchantId":"Esp5373790",
            "trxId":"INV-mMYzRKVdoVWy",
            "channelId":18,
            "amount":55000,
            "currency":"IDR",
            "status":103,
            "paidDate":"-",
            "paidAmount":0,
            "paidCurrency":"-",
            "referenceId":"C8AC14C10DF64C5390262B5DB5DA4890"
        }';

        $data = json_decode($json, true);

        if ($data['inquiryStatus'] == 100) {


            $status = '';
            if ($data['status'] == 100) {
                $status = "the transaction has been successfully paid by customer.";
            } else if ($data['status'] == 102) {
                $status = "payment request has been successfully received by GOCPay and waiting for payment by customer.";
            } else {
                $status = "payment request has been forwarded to payment channel and waiting for payment by customer.";
            }

            echo "Status GOC <br>";
            echo "<hr>";
            echo "Status Inquiry: " . $data['inquiryStatus'] . "<br>";
            // echo "ID Merchant: " . $data['merchantId'] . "<br>";
            echo "ID Transaksi: " . $data['trxId'] . "<br>";
            echo "Jumlah: " . $data['amount'] . "<br>";
            echo "Mata Uang: " . $data['currency'] . "<br>";
            echo "Status Code: " . $data['status'] . "<br>";
            echo "Status: " . $status . "<br>";
            echo "Tanggal Pembayaran: " . $data['paidDate'] . "<br>";
            echo "Jumlah Pembayaran: " . $data['paidAmount'] . "<br>";
            echo "Mata Uang Pembayaran: " . $data['paidCurrency'] . "<br>";
            // echo "ID Referensi: " . $data['referenceId'] . "<br>";

            echo "<hr>";
            echo "---------------------Detail Transaction-------------------- <br>";
        } else if ($data['inquiryStatus'] == 201) {
            echo "transaction not found";
        } else {
            echo "some thing wrong";
        }
    }
}
