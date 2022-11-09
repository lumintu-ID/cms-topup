<?php

namespace App\Http\Controllers\cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function notify(Request $request)
    {
        Log::critical('Critical error', $data);
        Log::info('info', ['data' => $data]);
        Log::error('error', ['data' => $data]);
        Log::warning('warning', ['data' => $data]);

        return Response()->json([
            'data' => $request->all()
        ]);
    }
}
