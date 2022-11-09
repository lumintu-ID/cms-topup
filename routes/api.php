<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Route;
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
});

Route::post('/inquiry', function (Request $request) {

    $merchanId = $request->merchantId;
    $trxId = $request->trxId;
    $hashKey = $request->hashKey;
    $sign = hash('sha256', $merchanId . $trxId . $hashKey);


    $data = array(
        'merchanId' => $merchanId,
        'trxId' => $trxId,
        'sign' => $sign
    );

    $response = Http::asForm()->post('https://pay.goc.id/inquiry/', $data);

    return  $response->json();
});
