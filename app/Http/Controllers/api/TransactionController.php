<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Price;
use App\Models\Payment;
use App\Models\GameList;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trxId' => 'required',
            'email' => 'required',
            'game_id' => 'required',
            'payment_id' => 'required',
            'price_id' => 'required',
            'total' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'Validation_Failed',
                'error' => $validator->errors(),
            ], 422);
        };

        DB::beginTransaction();
        try {

            $game = GameList::where('id', $request->game_id)->get();

            if (count($game) == 0) {
                Log::warning('Game Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | data not found ']);

                return response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Not Found',
                ], 404);
            };


            $payment = Payment::where('payment_id', $request->payment_id)->get();

            if (count($payment) == 0) {
                Log::warning('Payment Method Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | data not found ']);

                return response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Not Found',
                ], 404);
            };

            $price = Price::where('price_id', $request->price_id)->get();

            if (count($price) == 0) {
                Log::warning('Price List Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | data not found ']);

                return response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Not Found',
                ], 404);
            };

            Transaction::create([
                'transaction_id' => $request->trxId,
                'game_id' => $request->game_id,
                'email' => $request->email,
                'method_payment' => $request->payment_id,
                'product_name' => $price[0]->title_price,
                'amount' => $request->total,
                'status' => 1
            ]);


            DB::commit();
            Log::info('Success Request Transaction', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Post Transaction data']);

            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Post Request Transaction',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {

            DB::rollback();
            Log::error('Error Get Payment List', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Get Payment List']);
            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
