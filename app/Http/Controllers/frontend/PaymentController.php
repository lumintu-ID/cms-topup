<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Repository\Frontend\GeneralRepository;
use App\Services\Frontend\Invoice\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService, GeneralRepository $generalRepository)
    {
        $this->invoiceService = $invoiceService;
        $this->generalRepository = $generalRepository;
    }

    public function index(Request $request)
    {
        try {
            $slug = $request->slug;
            $dataGame = $this->generalRepository->getDataGameBySlug($slug);
            $countries = $this->generalRepository->getAllDataCountry();
            
            return view('frontend.payment.index', compact('countries', 'dataGame'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function confirmation(Request $request) 
    {  
        try {
            if(!$request->query('invoice')) return dd('not found');
            $data = $this->invoiceService->getInvoice($request->query('invoice'));
            
            return response()->view('frontend.payment.confirmation', compact('data'))
            ->header('Access-Control-Allow-Origin', 'https://dev.unipin.com/api/unibox/request')
            ->header('Access-Control-Allow-Methods', 'POST')
            ->header('Access-Control-Allow-Headers', '*');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function test(Request $request)
    {
        dd(json_encode($request->all()));
    }

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


