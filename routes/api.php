<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cms\TransactionController;
use App\Http\Controllers\api\GameController as ApiGameController;
use App\Http\Controllers\api\PaymentController as ApiPaymentController;
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
    Route::get('/gamelist', [ApiGameController::class, 'index']);
    Route::get('/gamedetail', [ApiGameController::class, 'gameDetail']);


    Route::get('/payment', [ApiPaymentController::class, 'index']);

    Route::get('/allpayment', [ApiPaymentController::class, 'getAllPayment']);
});

Route::post('/administrator/transaction/notify', [TransactionController::class, 'notify']);
