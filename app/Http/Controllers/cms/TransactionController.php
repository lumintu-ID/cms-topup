<?php

namespace App\Http\Controllers\cms;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{

    protected $data = array();

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
