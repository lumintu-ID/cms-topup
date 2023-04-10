<?php

namespace App\Http\Controllers\cms;

use App\Helpers\Goc;
use App\Helpers\Coda;

use App\Helpers\Razor;
use App\Helpers\Unipin;
use App\Models\GameList;
use App\Helpers\MotionPay;
use App\Models\Transaction;
use App\Models\Code_payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\GudangVoucher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


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

        $result = null;

        Log::info('info', ['data' => $request->all()]);

        if ($request->trans_id) {
            // Motion Pay
            $result =  MotionPay::UpdateStatus($request);
        } else if ($request->data) {
            // GV
            $result = GudangVoucher::UpdateStatus($request);
        } else if ($request->applicationCode) {
            // Razor
            $result = Razor::UpdateStatus($request);
        } else if ($request->transaction) {
            // Unipin ;
            $result = Unipin::UpdateStatus($request);
        } else if ($request->TxnId) {
            // Coda
            $result = Coda::UpdateStatus($request);
        } else {
            // GOC ;
            $result = Goc::UpdateStatus($request);
        };


        return $result;
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
