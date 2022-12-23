<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Repository\Frontend\GeneralRepository;
use App\Services\Frontend\Invoice\InvoiceService;
use Illuminate\Http\Request;


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
            
            return view('frontend.payment.confirmation', compact('data'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function test(Request $request)
    {
        dd(json_encode($request->all()));
    }
    
    
}


