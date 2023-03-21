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
            $data = array();
            $now = Carbon::now();
            $title = "Transaction History";
            $game = GameList::all();

            $status = request('status');
            $game_id = request('game_list');
            $start_date = request('start_date');
            $end_date = request('end_date');


            // membuat query builder untuk tabel transactions
            $query = Transaction::query();

            // join ke tabel price
            $query->join('prices', 'prices.price_id', '=', 'transactions.price_id');

            // join ke tabel pricepoint
            $query->join('price_points', 'price_points.id', '=', 'transactions.price_point_id');

            // join ke tabel payment
            $query->join('payments', 'payments.payment_id', '=', 'transactions.method_payment');

            // join ke tabel game
            $query->join('game_lists', 'game_lists.id', '=', 'transactions.game_id');


            // filter berdasarkan status
            if ($status != null && $status != "Select Status Transaction") {
                // dd($status);
                $query->where('transactions.status', $status);
            }

            // filter berdasarkan daftar game
            if ($game_id != null && $game_id != "Select Game") {
                // dd($game_id);
                $query->where('transactions.game_id', $game_id);
            }


            // filter berdasarkan rentang tanggal
            if ($start_date != null) {
                // dd('start');
                $query->whereDate('transactions.created_at', '>=', $start_date);
            }

            if ($end_date != null) {
                // dd('end');
                $query->whereDate('transactions.created_at', '<=', $end_date);
            }

            if ($status == null && $game_id == null && $start_date == null && $end_date == null) {
                $query->whereDate('transactions.created_at', '=', $now);
            }

            // mengeksekusi query dan mendapatkan data yang sesuai dengan filter
            $data = $query->select(
                'transactions.invoice',
                'transactions.id_Player AS id_player',
                'transactions.game_id',
                'transactions.method_payment',
                'transactions.price_point_id',
                'transactions.price_id',
                'transactions.email',
                'transactions.phone',
                'transactions.amount',
                'transactions.total_price',
                'transactions.status',
                'transactions.created_at',
                'prices.name',
                'prices.price',
                'price_points.country_id',
                'price_points.price_point AS PPI',
                'payments.name_channel',
                'game_lists.game_title',
                'transactions.paid_time',

            )->orderBy('transactions.created_at', 'desc')->get();


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
