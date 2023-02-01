<?php

namespace App\Http\Controllers\cms;

use App\Models\Price;
use GuzzleHttp\Client;
use App\Models\Payment;
use App\Models\Transaction;

use Illuminate\Support\Str;
use App\Models\Code_payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Events\Transaction as EventsTransaction;
use App\Helpers\Goc;
use App\Helpers\GudangVoucher;
use App\Helpers\MotionPay;
use App\Helpers\Razor;
use App\Helpers\Unipin;

class TransactionController extends Controller
{

    protected $data = array();


    // private function _goc($request)
    // {

    //     if ($request->status == 100) {
    //         $status = 1;
    //         Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GOC Invoice ' . $request->trxId]);
    //     } else {

    //         $status = 2;
    //         Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GOC Invoice ' . $request->trxId]);
    //     };

    //     $trx = Transaction::where('invoice', $request->trxId)->update([
    //         'status' => $status
    //     ]);

    //     $detail = Transaction::where('invoice', $request->trxId)->first();

    //     $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

    //     TransactionDetail::create([
    //         'detail_id' => Str::uuid(),
    //         'invoice_id' => $detail->invoice,
    //         'player_id' => $detail->id_Player,
    //         'game_id' => $detail->game_id,
    //         'ppi' => $price->pricepoint->price_point,
    //         'method' => $price->payment->name_channel,
    //         'amount' => $price->amount . ' ' . $price->name,
    //         'total_paid' => $detail->total_price,
    //         'paid_time' => $request->paidDate,
    //     ]);
    // }

    // private function _unipin($request)
    // {

    //     if ($request['transaction']['status'] == 0) {
    //         $status = 1;
    //         Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GOC Invoice ' . $request['transaction']['reference']]);
    //     } else {

    //         $status = 2;
    //         Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GOC Invoice ' . $request['transaction']['reference']]);
    //     };

    //     $trx = Transaction::where('invoice', $request['transaction']['reference'])->update([
    //         'status' => $status
    //     ]);

    //     $detail = Transaction::where('invoice', $request['transaction']['reference'])->first();

    //     $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

    //     TransactionDetail::create([
    //         'detail_id' => Str::uuid(),
    //         'invoice_id' => $detail->invoice,
    //         'player_id' => $detail->id_Player,
    //         'game_id' => $detail->game_id,
    //         'ppi' => $price->pricepoint->price_point,
    //         'method' => $price->payment->name_channel,
    //         'amount' => $price->amount . ' ' . $price->name,
    //         'total_paid' => $detail->total_price,
    //         'paid_time' => date('d-m-Y H:i', $request['transaction']['time'])
    //     ]);
    // }

    // private function _gudangVoucher($request)
    // {
    //     $dataXML = $request->data;
    //     $xmlObject = simplexml_load_string($dataXML);

    //     $json = json_encode($xmlObject);
    //     $phpArray = json_decode($json, true);

    //     Log::info('info', ['data' => $phpArray]);
    //     EventsTransaction::dispatch($phpArray['custom']);

    //     if ($phpArray['status'] == "SUCCESS") {
    //         $status = 1;
    //         Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GV Invoice ' . $phpArray['custom']]);
    //     } else {
    //         $status = 2;
    //         Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GV Invoice ' . $phpArray['custom']]);
    //     };

    //     $trx = Transaction::where('invoice', $phpArray['custom'])->update([
    //         'status' => $status
    //     ]);

    //     $detail = Transaction::where('invoice', $phpArray['custom'])->first();

    //     $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

    //     TransactionDetail::create([
    //         'detail_id' => Str::uuid(),
    //         'invoice_id' => $detail->invoice,
    //         'player_id' => $detail->id_Player,
    //         'game_id' => $detail->game_id,
    //         'ppi' => $price->pricepoint->price_point,
    //         'method' => $price->payment->name_channel,
    //         'amount' => $price->amount . ' ' . $price->name,
    //         'total_paid' => $detail->total_price,
    //         'paid_time' => date('d-m-Y H:i', $phpArray['payment_time']),
    //     ]);
    // }

    // private function _razorGold($request)
    // {

    //     if ($request->paymentStatusCode == 00) {
    //         $status = 1;
    //         Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with Razor GOLD Invoice ' . $request->referenceId]);
    //     } else {

    //         $status = 2;
    //         Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with Razor GOLD Invoice ' . $request->referenceId]);
    //     };

    //     $trx = Transaction::where('invoice', $request->referenceId)->update([
    //         'status' => $status
    //     ]);

    //     $detail = Transaction::where('invoice', $request->referenceId)->first();

    //     $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

    //     TransactionDetail::create([
    //         'detail_id' => Str::uuid(),
    //         'invoice_id' => $detail->invoice,
    //         'player_id' => $detail->id_Player,
    //         'game_id' => $detail->game_id,
    //         'ppi' => $price->pricepoint->price_point,
    //         'method' => $price->payment->name_channel,
    //         'amount' => $price->amount . ' ' . $price->name,
    //         'total_paid' => $detail->total_price,
    //         'paid_time' => $request->paymentStatusDate
    //     ]);

    //     Log::info('info', ['data' => $request->all()]);
    //     EventsTransaction::dispatch($request->referenceId);
    // }

    // private function _motionPay($request)
    // {
    //     if ($request->status_code == 200) {
    //         $status = 1;
    //         Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with Motion Pay Invoice ' . $request->order_id]);
    //     } else {

    //         $status = 2;
    //         Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with Motion Pay Invoice ' . $request->order_id]);
    //     };

    //     $trx = Transaction::where('invoice', $request->order_id)->update([
    //         'status' => $status
    //     ]);

    //     $detail = Transaction::where('invoice', $request->order_id)->first();

    //     $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

    //     if ($status == 1) {
    //         TransactionDetail::create([
    //             'detail_id' => Str::uuid(),
    //             'invoice_id' => $detail->invoice,
    //             'player_id' => $detail->id_Player,
    //             'game_id' => $detail->game_id,
    //             'ppi' => $price->pricepoint->price_point,
    //             'method' => $price->payment->name_channel,
    //             'amount' => $price->amount . ' ' . $price->name,
    //             'total_paid' => $detail->total_price,
    //             'paid_time' => $request->datetime_payment
    //         ]);
    //     };

    //     Log::info('info', ['data' => $request->all()]);
    //     EventsTransaction::dispatch($request->order_id);
    // }

    public function index(Request $request)
    {
        try {


            $title = "Transaction History";

            $data = Transaction::with('price', 'pricepoint', 'payment', 'game', 'transactionDetail')->orderBy('created_at', 'desc')->get();

            return view('cms.pages.transaction.index', compact('title', 'data'));
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }

    public function notify(Request $request)
    {


        $trx = null;
        $status = null;

        DB::beginTransaction();
        try {

            if ($request->trans_id) {
                // Motion Pay

                MotionPay::UpdateStatus($request);
                // $this->_motionPay($request);
            } else if ($request->data) {
                // GV

                GudangVoucher::UpdateStatus($request);

                // $this->_gudangVoucher($request);
            } else if ($request->applicationCode) {

                // Razor


                Razor::UpdateStatus($request);
                // Log::info('info', ['data' => $request->all()]);
                // EventsTransaction::dispatch($request->referenceId);
                // $this->_razorGold($request);
            } else if ($request->transaction) {

                // Unipin ;

                Unipin::UpdateStatus($request);


                // Log::info('info', ['data' => $request->all()]);
                // EventsTransaction::dispatch($request->transaction['reference']);
                // $this->_unipin($request);
            } else {

                // GOC ;

                Goc::UpdateStatus($request);

                // Log::info('info', ['data' => $request->all()]);
                // EventsTransaction::dispatch($request->trxId);
                // $this->_goc($request);
            };

            DB::commit();

            if ($request->transaction) {
                return \response()->json([
                    'status' => $request['transaction']['status'],
                    'message' => 'Reload Successful',
                ], Response::HTTP_OK);
            } else if ($request->applicationCode) {
                return \response()->json([
                    'status' => 200,
                    'message' => '200 OK',
                ], Response::HTTP_OK);
            } else {
                return "OK";
            };
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error Notify TopUp Transaction ', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Notify TopUp Transaction']);

            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }



    // Check Status Transaction
    public function check(Request $request)
    {
        $trx = Transaction::with('payment')->where('invoice', $request->invoice)->first();

        if (!$trx) {
            $notif = array(
                'message' => 'Data Invoice Not Found',
                'alert-info' => 'warning'
            );

            return $notif;
        };

        $code_payment = Code_payment::where('id', $trx->payment->code_payment)->first();

        if ($code_payment->code_payment == 'GV') {

            GudangVoucher::Check($request);
            // $Merchantkey = env('GV_MERCHANT_KEY');
            // $Merchantid = env('GV_MERCHANT_ID');
            // $custom = $request->invoice;
            // $signature = md5($Merchantkey . $Merchantid . $custom);



            // $response = Http::get('https://www.gudangvoucher.com/cpayment.php?merchantid=' . $Merchantid . '&custom=' . $custom . 'signature=' . $signature . '');

            // return response()->json([
            //     'code' => 200,
            //     'status' => 'SUCCESS',
            //     'data' => $response->body(),
            // ], 200);
        } else if ($code_payment->code_payment == 'GOC') {


            GOC::Check($request);

            // $hashKey = env('GOC_HASHKEY');
            // $Merchantid = env('GOC_MERCHANT_ID');
            // $trxId = $request->invoice;

            // $signature = hash('sha256', $Merchantid . $trxId . $hashKey);

            // $response = Http::asForm()->post('https://pay.goc.id/inquiry/', [
            //     'merchantId' => $Merchantid,
            //     'trxId' => $trxId,
            //     'sign' => $signature
            // ]);

            // // $this->_goc($response->json());

            // return response()->json([
            //     'code' => 200,
            //     'status' => 'SUCCESS',
            //     'data' => $response->body(),
            // ], 200);
        } else if ($code_payment->code_payment == 'UNIPIN') {

            Unipin::Check($request);

            // $guid = env('UNIPIN_DEV_GUID');
            // $secret = env('UNIPIN_DEV_SECRET_KEY');
            // $trxId = $request->invoice;

            // $signature = hash('sha256', $guid . $trxId . $secret);

            // $data = [
            //     'guid' => $guid,
            //     'reference' => $trxId,
            //     'signature' => $signature
            // ];

            // $client = new Client();
            // $response = $client->request('POST', 'https://dev.unipin.com/api/unibox/inquiry', [
            //     'headers' => ['Content-type' => 'application/json'],
            //     'body' => json_encode($data),
            // ]);


            // return response()->json([
            //     'code' => 200,
            //     'status' => 'SUCCESS',
            //     'data' => json_decode($response->getBody()->getContents(), true),
            // ], 200);
        } else {
            return response()->json([
                'code' => 200,
                'status' => 'SUCCESS',
                'data' => 'salah',
            ], 200);
        }
    }
}
