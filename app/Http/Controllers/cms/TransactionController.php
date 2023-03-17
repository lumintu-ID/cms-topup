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
use App\Models\GameList;

class TransactionController extends Controller
{

    protected $data = array();


    public function index(Request $request)
    {
        try {
            $now = Carbon::now();

            $title = "Transaction History";
            $game = GameList::all();

            $data = Transaction::where('created_at', $now)->with('price', 'pricepoint', 'payment', 'game', 'transactionDetail')->orderBy('created_at', 'desc')->get();

            return view('cms.pages.transaction.index', compact('title', 'game', 'data'));
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

            Log::info('info', ['data' => $request->all()]);

            if ($request->trans_id) {
                // Motion Pay

                MotionPay::UpdateStatus($request);
            } else if ($request->data) {
                // GV

                GudangVoucher::UpdateStatus($request);
            } else if ($request->applicationCode) {

                // Razor


                Razor::UpdateStatus($request);
                // Log::info('info', ['data' => $request->all()]);
                // EventsTransaction::dispatch($request->referenceId);

            } else if ($request->transaction) {

                // Unipin ;

                Unipin::UpdateStatus($request);


                // Log::info('info', ['data' => $request->all()]);
                // EventsTransaction::dispatch($request->transaction['reference']);

            } else {

                // GOC ;

                Goc::UpdateStatus($request);

                // Log::info('info', ['data' => $request->all()]);
                // EventsTransaction::dispatch($request->trxId);

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
        } else if ($code_payment->code_payment == 'GOC') {


            GOC::Check($request);
        } else if ($code_payment->code_payment == 'UNIPIN') {

            Unipin::Check($request);
        } else if ($code_payment->code_payment == 'MOTIONPAY') {

            MotionPay::Check($request);
        } else if ($code_payment->code_payment == 'RAZOR') {
            Razor::Check($request);
        } else {
            return response()->json([
                'code' => 200,
                'status' => 'SUCCESS',
                'data' => 'salah',
            ], 200);
        }
    }
}
