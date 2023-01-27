<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\Invoice\InvoiceService;
use App\Services\Frontend\Payment\PaymentService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
            'warning' => 'ID Player is required',
            'playerNotFound' => 'error, please try again'
        ],
        'titleModal' => [
            'purchase' => 'Detail Purchases',
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
            if ($request->slug) {
                $slug = $request->slug;
                $dataGame = $this->_paymentService->getDataGame($slug);
                $countries = $this->_paymentService->getAllDataCountry();
                $activeLink = $this->activeLink;
                $textAttribute = json_encode($this->dataset);
                $categoryPayment = json_encode($this->_paymentService->getAllCategoryPayment());

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
            if (!$request->query('invoice')) return 'not found';
            // dd($request->query('invoice'));
            $data = $this->_invoiceService->getInvoice($request->query('invoice'));
            $activeLink = $this->activeLink;

            // dd( $data);

            return response()->view('frontend.payment.confirmation', compact('data', 'activeLink'));
            // ->header('Access-Control-Allow-Origin', 'https://dev.unipin.com/api/unibox/request')
            // ->header('Access-Control-Allow-Methods', 'POST')
            // ->header('Access-Control-Allow-Headers', '*');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function parseToVendor(Request $request)
    {
        dd($request->all());
        try {
            $uri = "https://globalapi.gold-sandbox.razer.com/payout/payments";
            $client = new Client();
            $response = $client->request('POST', $uri, [
                'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    "applicationCode" => $request->applicationCode,
                    "referenceId" => $request->referenceId,
                    "version" => $request->version,
                    "amount" => $request->amount,
                    "currencyCode" => $request->currencyCode,
                    "returnUrl" => $request->returnUrl,
                    "description" => $request->description,
                    "customerId" => $request->customerId,
                    "hashType" => $request->hashType,
                    "signature" => $request->signature,
                ]
            ]);
            $dataResponse = json_decode($response->getBody()->getContents(), true);
            if ($dataResponse['paymentUrl']) return redirect($dataResponse['paymentUrl']);
        } catch (RequestException $error) {
            $responseError = json_decode($error->getResponse()->getBody()->getContents(), true);
            echo 'Error message: ' . $responseError['message'];
        }
    }
}
