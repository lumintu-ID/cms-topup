<?php

namespace App\Http\Controllers\cms;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\Transaction as EventsTransaction;

class TransactionController extends Controller
{

    protected $data = array();

    public function notify(Request $request)
    {
        Log::critical('Critical error', $request->all());
        Log::info('info', ['data' => $request->all()]);
        Log::error('error', ['data' => $request->all()]);
        Log::warning('warning', ['data' => $request->all()]);

        Transaction::create([
            'Transaction_id' => Str::uuid(),
            'game_id' => $request->game_id,
            'method_payment' => $request->method_payment,
            'product_name' => $request->product_name,
            'email' => $request->email,
            'amount' => $request->amount,
            'status' => $request->status
        ]);

        EventsTransaction::dispatch($request->all());

        return response()->json($request->all());
    }

    public function index(Request $request)
    {
        try {


            $title = "Transaction History";

            $data = Transaction::orderBy('created_at', 'desc')->get();

            return view('cms.pages.transaction.index', compact('title', 'data'));
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }
}
