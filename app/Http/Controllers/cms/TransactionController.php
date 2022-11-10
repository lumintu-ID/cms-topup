<?php

namespace App\Http\Controllers\cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function notify(Request $request)
    {
        Log::critical('Critical error', $request->all());
        Log::info('info', ['data' => $request->all()]);
        Log::error('error', ['data' => $request->all()]);
        Log::warning('warning', ['data' => $request->all()]);

        return Response()->json([
            'data' => $request->all()
        ]);
    }

    public function index(Request $request)
    {
        try {
            $title = "Transaction History";

            $data = Transaction::orderBy('created_at', 'asc')->get();

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
