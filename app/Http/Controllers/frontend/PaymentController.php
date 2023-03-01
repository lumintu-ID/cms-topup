<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\Invoice\InvoiceService;
use App\Services\Frontend\Payment\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $_invoiceService;
    private $_paymentService;
    private $activeLink = 'payment';
    private $dataset = [
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
            $activeLink = $this->activeLink;
            if ($request->slug) {
                $slug = $request->slug;
                $dataGame = $this->_paymentService->getDataGame($slug);
                $countries = $this->_paymentService->getAllDataCountry();
                $textAttribute = json_encode($this->dataset);
                $categoryPayment = json_encode($this->_paymentService->getAllCategoryPayment());

                return view('frontend.payment.index', compact('countries', 'dataGame', 'activeLink', 'textAttribute', 'categoryPayment'));
            }

            return view('frontend.payment.check-invoice', compact('activeLink'));
        } catch (\Throwable $th) {
            abort(500);
        }
    }

    public function confirmation(Request $request)
    {
        try {
            if (!$request->query('invoice')) return 'Not found';

            $activeLink = $this->activeLink;
            $data = $this->_invoiceService->getInvoice($request->query('invoice'));

            if (empty($data['attribute'])) {
                return view('frontend.payment.confirmation-success', compact('data', 'activeLink'));
            }

            if (!empty($data['attribute']['va_number'])) {
                return view('frontend.payment.confirmation-va', compact('data', 'activeLink'));
            }

            return response()->view('frontend.payment.confirmation', compact('data', 'activeLink'));
        } catch (\Throwable $error) {
            abort(404);
        }
    }

    public function parseToVendor(Request $request)
    {
        try {
            $urlRedirect = $this->_invoiceService->redirectToPayment($request->code, $request->all());
            if ($urlRedirect) {
                return redirect($urlRedirect);
            }
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

    public function success()
    {
        echo 'success page';
    }
}
