<?php

namespace App\Http\Controllers\cms;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\Transaction as EventsTransaction;

class TransactionController extends Controller
{

    protected $data = array();

    public function index(Request $request)
    {
        try {


            $title = "Transaction History";

            $data = Transaction::with('pricepoint', 'payment', 'game')->orderBy('created_at', 'desc')->get();


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

        Log::critical('Critical error', $request->all());
        Log::info('info', ['data' => $request->all()]);
        Log::error('error', ['data' => $request->all()]);
        Log::warning('warning', ['data' => $request->all()]);

        DB::beginTransaction();
        try {

            if ($request->data) {
                // GV

                $dataXML = $request->data;
                $xmlObject = simplexml_load_string($dataXML);

                $json = json_encode($xmlObject);
                $phpArray = json_decode($json, true);

                Log::info('info', ['data' => $phpArray]);
                // Log::error('error', ['data' => $phpArray['status']]);
                // Log::warning('warning', ['data' => $phpArray['custom']]);
                EventsTransaction::dispatch($phpArray['custom']);


                if ($phpArray['status'] == "SUCCESS") {
                    $status = 2;
                    Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GV Invoice ' . $phpArray['custom']]);
                } else {
                    $status = 1;
                    Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GV Invoice ' . $phpArray['custom']]);
                };

                $trx = Transaction::where('invoice', $phpArray['custom'])->update([
                    'status' => $status
                ]);

                $detail = Transaction::where('invoice', $phpArray['custom'])->first();

                $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

                TransactionDetail::create([
                    'detail_id' => Str::uuid(),
                    'invoice_id' => $detail->trxId,
                    'player_id' => $detail->id_player,
                    'game_id' => $detail->game_id,
                    'ppi' => $price->pricepoint->price_point,
                    'method' => $price->payment->name_channel,
                    'amount' => $price->amount . ' ' . $price->name,
                    'total_paid' => $detail->total_price,
                    'paid_time' => date('d-m-Y H:i', $phpArray['payment_time']),
                ]);
            } else {

                // GOC ;

                Log::info('info', ['data' => $request->all()]);
                EventsTransaction::dispatch($request->trxId);

                if ($request->status == 100) {
                    $status = 2;
                    Log::info('Success Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Transaction Paid with GOC Invoice ' . $request->trxId]);
                } else {

                    $status = 0;
                    Log::info('Cancel Transaction Paid', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Cancel Transaction Paid with GOC Invoice ' . $request->trxId]);
                };

                $trx = Transaction::where('invoice', $request->trxId)->update([
                    'status' => $status
                ]);

                $detail = Transaction::where('invoice', $request->trxId)->first();

                $price = Price::with('payment', 'pricepoint')->where('price_id', $detail->price_id)->first();

                TransactionDetail::create([
                    'detail_id' => Str::uuid(),
                    'invoice_id' => $detail->trxId,
                    'player_id' => $detail->id_player,
                    'game_id' => $detail->game_id,
                    'ppi' => $price->pricepoint->price_point,
                    'method' => $price->payment->name_channel,
                    'amount' => $price->amount . ' ' . $price->name,
                    'total_paid' => $detail->total_price,
                    'paid_time' => $request->paidDate,
                ]);
            };

            DB::commit();

            return "OK";
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
}
