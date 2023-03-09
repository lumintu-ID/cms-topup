<?php

namespace App\Helpers;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Http;
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



        Transaction::where('invoice', $request->referenceId)->update([
            'status' => $status
        ]);



        $detail = Transaction::where('invoice', $request->referenceId)->first();



        $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();



        if ($status == 1) {
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
        };
    }

    public static function Check($request)
    {
        $applicationCode = env('RAZOR_MERCHANT_CODE');
        $seceretkey =  env('RAZOR_SECRET_KEY');
        $hashType = 'hmac-sha256';
        $referenceId = $request->invoice;
        $version = 'v1';



        $dataSIGN = $applicationCode . $hashType . $referenceId . $version;
        $sign = hash_hmac('sha256', $dataSIGN, $seceretkey);

        $response = Http::get('https://globalapi.gold-sandbox.razer.com/payout/payments?applicationCode=' . $applicationCode . '&referenceId=' . $referenceId . '&version=' . $version  . '&hashType=' . $hashType . '&signature=' . $sign);


        $result = $response->body();

        // json{
        //     "paymentId": "MPO2038352",
        //     "referenceId": "INV-VSV0bRfv8boj",
        //     "paymentStatusCode": "00",
        //     "paymentStatusDate": "2023-03-08T06:22:31Z",
        //     "amount": 2005000,
        //     "currencyCode": "IDR",
        //     "customerId": "21324234",
        //     "virtualCurrencyAmount": "",
        //     "hashType": "hmac-sha256",
        //     "version": "v1",
        //     "signature": "dfbbaae2fab49cd600aa705f7a2648bd01b15599ea5f5b88eb13f58056f524bf",
        //     "applicationCode": "WG12Nu61SaXhQieGcmW7yYWhKp9xBwvn"
        // }

        // Mengkonversi JSON string ke array PHP
        $data = json_decode($result, true);

        echo "Status Razor <br>";
        echo "<hr>";
        // Menampilkan data
        echo "Payment ID: " . $data['paymentId'] . "<br>";
        echo "Reference ID: " . $data['referenceId'] . "<br>";
        echo "Payment Status Code: " . $data['paymentStatusCode'] . "<br>";
        echo "Payment Status Date: " . $data['paymentStatusDate'] . "<br>";
        echo "Amount: " . $data['amount'] . "<br>";
        echo "Currency Code: " . $data['currencyCode'] . "<br>";
        echo "Customer ID: " . $data['customerId'] . "<br>";
        echo "Virtual Currency Amount: " . $data['virtualCurrencyAmount'] . "<br>";
        echo "Hash Type: " . $data['hashType'] . "<br>";
        echo "Version: " . $data['version'] . "<br>";
        echo "Signature: " . $data['signature'] . "<br>";
        echo "Application Code: " . $data['applicationCode'] . "<br>";

        echo "<hr>";
        echo "---------------------Detail Transaction-------------------- <br>";
    }
}
