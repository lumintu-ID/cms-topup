<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('pay', function (Request $request) {


    $merchanId = 'Esp5373790';
    $trxId = Str::random(10);
    $trxDate = date(now());

    $sign = hash('sha256', $merchanId . $trxId . $trxDate . $request->channelId . $request->amount . 'IDR' . 'jqji815m748z0ql560982426ca0j70qk02411d2no6u94qgdf58js2jn596s99si');


    $data = [
        'merchantId' => $merchanId,
        'trxId' => $trxId,
        'trxDateTime' => $trxDate,
        'channelId' => $request->channelId,
        'amount'    => $request->amount,
        'returnUrl' => $request->returnUrl,
        'currency'  => 'IDR',
        'sign' => $sign
    ];

    $response = Http::asForm()->post("https://pay.goc.id/", $data);


    return $response;
});
