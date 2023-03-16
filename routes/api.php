<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\GameController as ApiGameController;
use App\Http\Controllers\api\PaymentController as ApiPaymentController;
use App\Http\Controllers\cms\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/player', function (Request $request) {
        try {

            if ($request->query('player_id') == 1234567890) {
                $result = [
                    'id' => $request->query('player_id'),
                    'username' => "demo_player",
                    'email' => "demo@gmail.com",
                ];

                return \response()->json([
                    'code' => 200,
                    'status' => 'OK',
                    'message' => 'Success Get Player id ' . $request->query('player_id'),
                    'data' => $result
                ], 200);
            } else {
                return \response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'message' => 'Data ID Player' . $request->query('player_id') . ' not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return \response()->json([
                'code' => 300,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], 300);
        }
    });


    // game list
    Route::get('/gamelist', [ApiGameController::class, 'index']);
    Route::get('/gamedetail', [ApiGameController::class, 'gameDetail']);


    // payment
    Route::get('/payment', [ApiPaymentController::class, 'index']);

    Route::get('/allpayment', [ApiPaymentController::class, 'getAllPayment']);


    Route::post('/transaction/notify', [TransactionController::class, 'notify']);
});
