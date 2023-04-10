<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\Invoice\InvoiceService;
use App\Services\Frontend\Payment\PaymentService;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $_invoiceService;
    private $_paymentService;
    private $_activeLink = 'payment';
    private $_dataset = [
        'infoTextInput' => [
            'idPlayer' => 'Please input your id.',
            'country' => 'Please choose your country.',
            'warning' => 'ID Player is required.',
            'playerNotFound' => 'Error, please try again.'
        ],
        'titleModal' => [
            'purchase' => 'Detail Purchases',
            'alertInfo' => 'Alert',
        ],
        'alert' => [
            'idPlayer' => 'Id player is required.',
            'checkIdPlayer' => 'Please check your id.',
            'country' => 'Country must be choosed.',
            'payment' => 'Payment must be choosed.',
            'phone' => 'Phone number is required.',
            'item' => 'Item must be choosed.',
            'notAvaliable' => 'Data not avaliable'
        ],
        'noPayment' => 'Payment not avaliable',
        'badRequest' => 'Trouble in internal system, please wait.'
    ];

    public function __construct(InvoiceService $invoiceService, PaymentService $paymentService)
    {
        $this->_invoiceService = $invoiceService;
        $this->_paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        try {
            $activeLink = $this->_activeLink;
            if ($request->slug) {
                $slug = $request->slug;
                $dataGame = $this->_paymentService->getDataGame($slug);
                $countries = $this->_paymentService->getAllDataCountry();
                $textAttribute = json_encode($this->_dataset);
                $categoryPayment = json_encode($this->_paymentService->getAllCategoryPayment());

                if (!$dataGame) throw new Exception('Not Found Game', 404);

                return view('frontend.payment.index', compact('countries', 'dataGame', 'activeLink', 'textAttribute', 'categoryPayment'));
            }

            return view('frontend.payment.check-invoice', compact('activeLink'));
        } catch (Exception $th) {
            abort($th->getCode());
        }
    }

    public function confirmation(Request $request)
    {
        try {
            if (!$request->query('invoice')) return 'Not found';

            $activeLink = $this->_activeLink;
            $data = $this->_invoiceService->getInvoice($request->query('invoice'));
            $alert = $this->_dataset['alert']['notAvaliable'];

            if (empty($data['attribute'])) {
                return view('frontend.payment.confirmation-success', compact('data', 'activeLink', 'alert'));
            }

            if (!empty($data['attribute']['va_number'])) {
                return view('frontend.payment.confirmation-va', compact('data', 'activeLink', 'alert'));
            }

            return response()->view('frontend.payment.confirmation', compact('data', 'activeLink', 'alert'));
        } catch (\Throwable $error) {
            abort(404);
        }
    }

    public function parseToVendor(Request $request)
    {
        try {
            $urlRedirect = $this->_invoiceService->redirectToPayment($request->code, $request->all());
            if ($urlRedirect) return redirect($urlRedirect);
        } catch (\Throwable $error) {
            abort($error->getCode(), $error->getMessage());
        }
    }

    public function infoPayment(Request $request)
    {
        try {
            if ($request) {
                $data = $this->_invoiceService->confrimInfo($request->all());
                echo $data['message'];
                return;
            }
        } catch (\Throwable $error) {
            abort($error->getCode(), $error->getMessage());
        }
    }

    public function checkInvoice(Request $request)
    {
        $activeLink = $this->_activeLink;
        if ($request['id']) {
            echo 'check invoice ' . $request['id'];
            return;
        }
        return view('frontend.payment.check-invoice2', compact('activeLink'));
    }

    public function codapayCheckout(Request $request)
    {
        try {
            $urlRedirect = $this->_invoiceService->redirectToPayment('CODA', $request->all());
            if ($urlRedirect) return redirect($urlRedirect);
        } catch (\Throwable $error) {
            abort($error->getCode(), $error->getMessage());
        }
        // dd($request->all());
        // $dataRedirect = $this->_invoiceService->redirectToPayment($request->codePayment, $request->all());
        // dd($dataRedirect);

        // return view('frontend.payment.codapay-checkout', compact('dataRedirect'));
    }
}
