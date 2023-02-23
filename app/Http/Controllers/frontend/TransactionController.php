<?php

namespace App\Http\Controllers\frontend;

use Carbon\Carbon;
use App\Models\Ppn;
use App\Models\Price;
use App\Models\Payment;
use App\Models\GameList;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\TransactionRequest;
use App\Events\Transaction as EventsTransaction;

class TransactionController extends Controller
{

    private function _sendEmail($request)
    {
        EventsTransaction::dispatch($request->email);
        Mail::send('emails.invoice', ['email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });
    }

    public function transaction(TransactionRequest $request)
    {
        // dd('controller transaksi');
        DB::beginTransaction();
        try {

            $game = GameList::where('id', $request->game_id)->get();

            if (count($game) == 0) {
                Log::warning('Game Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | data not found ']);

                $notif = array(
                    'message' => 'Game not found',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };


            $payment = Payment::where('payment_id', $request->payment_id)->get();

            if (count($payment) == 0) {
                Log::warning('Payment Method Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | data not found ']);

                $notif = array(
                    'message' => 'Payment not found',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $price = Price::with('pricepoint')->where('price_id', $request->price_id)->get();

            if (count($price) == 0) {
                Log::warning('Price List Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | data not found ']);

                $notif = array(
                    'message' => 'Price not found',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $invoice = "INV-" . Str::random(12);


            Transaction::create([
                'invoice' => $invoice,
                'game_id' => $request->game_id,
                'id_Player' => $request->player_id,
                'method_payment' => $request->payment_id,
                'price_point_id' => $price[0]->pricepoint->id,
                'price_id' => $request->price_id,
                'email' => $request->email,
                'phone' => $request->phone ? $request->phone : null,
                'amount' => $price[0]->amount . ' ' . $price[0]->name,
                'total_price' => $this->totalPrice($price[0]->price),
                'status' => 0
            ]);

            DB::commit();
            Log::info('Success Request Transaction', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Post Transaction data']);

            $notif = array(
                'message' => 'Success Checkout',
                'alert-info' => 'success'
            );

            return redirect()->route('payment.confirmation', ['invoice' => $invoice]);
        } catch (\Throwable $th) {

            DB::rollback();
            Log::error('Error Get Payment List', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Get Payment List']);
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }

    private function totalPrice($price)
    {
        $ppn = Ppn::select('id_ppn as id', 'ppn')->get()->toArray();
        $totalPrice = $price + $ppn[0]['ppn'];

        return $totalPrice;
    }
}
