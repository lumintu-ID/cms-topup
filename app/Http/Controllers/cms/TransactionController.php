<?php

namespace App\Http\Controllers\cms;

use App\Models\Transaction;
use Illuminate\Http\Request;
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


        if ($request->data) {
            // GV

            $dataXML = $request->data;
            $xmlObject = simplexml_load_string($dataXML);

            $json = json_encode($xmlObject);
            $phpArray = json_decode($json, true);

            Log::critical('Critical error', $phpArray);
            Log::info('info', ['data' => $phpArray]);
            Log::error('error', ['data' => $phpArray]);
            Log::warning('warning', ['data' => $phpArray]);

            EventsTransaction::dispatch($phpArray);

            if ($phpArray->status == "SUCCESS") {
                $status = 1;
            } else {
                $status = 2;
            };

            $trx = Transaction::where('invoice', $phpArray->custom)->update([
                'status' => $status
            ]);

            return 'OK';
        } else {

            // GOC ;

            Log::critical('Critical error', $request->all());
            Log::info('info', ['data' => $request->all()]);
            Log::error('error', ['data' => $request->all()]);
            Log::warning('warning', ['data' => $request->all()]);

            EventsTransaction::dispatch($request->all());

            if ($request->status == 100) {
                $status = 1;
            } else {
                $status = 2;
            };

            $trx = Transaction::where('invoice', $request->trxId)->update([
                'status' => $status
            ]);

            return 'OK';
        };
    }
}
