<?php

namespace App\Helpers;

use App\Models\Price;
use GuzzleHttp\Client;
use App\Models\Reference;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;

class MotionPay
{
    public static function UpdateStatus($request)
    {
        if ($request->status_code == 200) {
            $status = 1;
            Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with Motion Pay Invoice ' . $request->order_id]);
        } else {

            $status = 2;
            Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with Motion Pay Invoice ' . $request->order_id]);
        };

        $trx = Transaction::where('invoice', $request->order_id)->update([
            'status' => $status
        ]);

        $detail = Transaction::where('invoice', $request->order_id)->first();

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
                'paid_time' => $request->datetime_payment
            ]);
        };
    }

    public static function Check($request)
    {
        $url = "https://spg.flashmobile.co.id/switching_pg/check_status.php";
        $merchanCode = env('MOTIONPAY_MERCHANT_CODE');
        $secret = env('MOTIONPAY_SECRET_KEY');
        $orderId = $request->invoice;
        $transId = Reference::where('invoice', $orderId)->first();

        $signature =  $signature = hash('sha1', md5($merchanCode . $orderId . $transId->reference . $secret));

        $data = [
            'merchant_code' => $merchanCode,
            'order_id'      => $orderId,
            'trans_id'      => $transId->reference,
            'signature'     => $signature
        ];

        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers'   => ['Content-type' => 'application/json'],
            'body'      => json_encode($data),
        ]);


        $result = $response->getBody()->getContents();


        $json = '{
            "trans_id":"f01mvr7gk",
            "merchant_code":"FmSample",
            "order_id":"INV-Kyokip9BNYlr",
            "amount":"55000.00",
            "payment_method":"va_bca",
            "mask_card":"",
            "va_number":"15055Kyokip9BNYlr",
            "time_limit":"60",
            "status_code":"201",
            "status_desc":"pending",
            "fm_refnum":"f01mvr7gk",
            "datetime_payment":"",
            "approval_code":"",
            "signature":"1eff3ce3d07726874979357428245ced97c3470b"
        }';

        // Mengkonversi JSON string ke array PHP
        $data = json_decode($json, true);



        echo "Status Motion Pay <br>";
        echo "<hr>";
        // echo "trans_id: " . $data['trans_id'] . "<br>";
        // echo "merchant_code: " . $data['merchant_code'] . "<br>";
        echo "order_id: " . $data['order_id'] . "<br>";
        echo "amount: " . $data['amount'] . "<br>";
        echo "payment_method: " . $data['payment_method'] . "<br>";
        echo "mask_card: " . $data['mask_card'] . "<br>";
        echo "va_number: " . $data['va_number'] . "<br>";
        echo "time_limit: " . $data['time_limit'] . "<br>";
        echo "status_code: " . $data['status_code'] . "<br>";
        echo "status_desc: " . $data['status_desc'] . "<br>";
        // echo "fm_refnum: " . $data['fm_refnum'] . "<br>";
        echo "datetime_payment: " . $data['datetime_payment'] . "<br>";
        echo "approval_code: " . $data['approval_code'] . "<br>";
        // echo "signature: " . $data['signature'] . "<br>";

        echo "<hr>";
        echo "---------------------Detail Transaction-------------------- <br>";
    }
}
