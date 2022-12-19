<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\GameList;

use App\Services\Frontend\Invoice\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


class PaymentController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {   
        $slug = $request->slug;
        $dataGame = GameList::select('id', 'game_id', 'game_title', 'cover')->where('slug_game', $slug)->first();
        $countries = Country::all();

        return view('frontend.payment.index', compact('countries', 'dataGame'));
    }

    public function confirmation(Request $request) 
    {  
        if(!$request->query('invoice')) return dd('not found');

        $invoice = $this->invoiceService->getInvoice($request->query('invoice')); 
        // dd($invoice);
            
        return view('frontend.payment.confirmation', compact('invoice'));
    }

    public function generate(Request $request)
    {   
        $merchantId = "Esp5373790";
        $haskey = 'jqji815m748z0ql560982426ca0j70qk02411d2no6u94qgdf58js2jn596s99si';
        $trxId = Str::uuid()->toString();
        $currency = 'IDR';
        $amount = (float)$request->input('amount');
        $channelId = (int)$request->input('channelId');
        $date = date('Y-m-d H:i:s');
        $trxDateTime= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d\TH:i:s')."+07";
        
        $sign = hash('sha256', $merchantId.$trxId.$trxDateTime.$channelId.$amount.$currency.$haskey);
        $data = [
            "merchantId" => $merchantId,
            "trxId" => $trxId,
            "trxDateTime" => $trxDateTime,
            "channelId" => $channelId,
            "currency" => $currency,
            "sign" => $sign,
        ];
        return \response()->json([
            'code' => Response::HTTP_OK,
            'status' => 'OK',
            'message' => 'Success Get Game List',
            'data' => $data
        ], Response::HTTP_OK);
        
    }
    public function generategv(Request $request)
    {   
        $merchantId = "1138";
        $mercahtKey = '947f512d9b86b517a0070d5a';
        $trxId = mt_rand(1000000000,9999999999);
        $amount = (float)$request->input('amount');
        $product = "Stone";
        
        
        $sign = hash('md5', $merchantId.$amount.$mercahtKey.$trxId);
        
        $data = [
            "merchantId" => $merchantId,
            "trxId" => $trxId,
            "product" => $product,
            "sign" => $sign,
        ];
        return \response()->json([
            'code' => Response::HTTP_OK,
            'status' => 'OK',
            'message' => 'Success Get Game List',
            'data' => $data
        ], Response::HTTP_OK);
        
    }
}


