<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\Invoice\InvoiceService;
use App\Services\Frontend\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private $_invoiceService;
    private $_paymentService;
    private $activeLink = 'payment';
    private $dataset = [
        'infoTextInput' => [
            'idPlayer' => 'Please input your id',
            'country' => 'Please choose your country',
        ],
        'titleModal' => [
            'purchase' => 'Detail Puschase',
            'alertInfo' => 'Alert',
        ],
        'alert' => [
            'idPlayer' => 'Id player is required',
            'country' => 'Country must be choosed',
            'payment' => 'Payment must be choosed',
            'item' => 'Item must be choosed',
        ],
        'noPayment' => 'Payment not avaliable',
    ];

    public function __construct(InvoiceService $invoiceService, PaymentService $paymentService)
    {
        $this->_invoiceService = $invoiceService;
        $this->_paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        try {
            if($request->slug) {
                $slug = $request->slug;
                $dataGame = $this->_paymentService->getDataGame($slug);
                $countries = $this->_paymentService->getAllDataCountry();
                $activeLink = $this->activeLink;
                $textAttribute = json_encode($this->dataset);
                $categoryPayment = json_encode( $this->_paymentService->getAllCategoryPayment());

                return view('frontend.payment.index', compact('countries', 'dataGame', 'activeLink', 'textAttribute', 'categoryPayment'));
            }

            return redirect()->route('home');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function confirmation(Request $request) 
    {  
        // dd($request->query('invoice'));
        try {
            if(!$request->query('invoice')) return 'not found';
            // dd($request->query('invoice'));
            $data = $this->_invoiceService->getInvoice($request->query('invoice'));
            $activeLink = $this->activeLink;
            
            return response()->view('frontend.payment.confirmation', compact('data', 'activeLink'));
            // ->header('Access-Control-Allow-Origin', 'https://dev.unipin.com/api/unibox/request')
            // ->header('Access-Control-Allow-Methods', 'POST')
            // ->header('Access-Control-Allow-Headers', '*');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    // public function test(Request $request)
    // {
    //     dd(json_encode($request->all()));
        
    // }

    public function unipin(Request $request)
    {
        dd($request->all());
        $dataParse = [
            'guid' => $request->devGuid,
            'reference' => $request->reference,
            'urlAck' => $request->urlAck,
            'currency' => $request->currency,
            'remark' => $request->remark,
            'signature' => $request->signature,
            'denominations' => $request->denominations
        ];
        // dd(json_encode($dataParse));
        
        // $response = Http::accept('application/json')->post($request->urlPayment, $dataParse);
        $response = Http::get('https://jsonplaceholder.typicode.com/todos/1');

        // dd(json_encode($response));
        dd($response);

        // return Http::dd()->post($request->urlPayment, $dataParse);
        return dd($response);
    }
    
    
}


