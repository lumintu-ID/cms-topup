<?php

namespace App\Http\Controllers\cms;

use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Repository\Country\CountryImplement;
use App\Repository\Payment\PaymentImplement;
use App\Repository\Category\CategoryImplement;

class PaymentController extends Controller
{

    private function _upload($file)
    {
        $name_file = time() . "_" . $file->getClientOriginalName();
        $dir = 'image/';
        $file->move($dir, $name_file);

        return $name_file;
    }

    private function _remove($file, $data)
    {
        if ($file->file('thumbnail')) {
            File::delete('image/' . $data->logo_channel);
            return;
        };

        return;
    }


    protected $categoryImplement;
    protected $countryImplement;
    protected $paymentImplement;

    public function __construct(CategoryImplement $categoryImplement, CountryImplement $countryImplement, PaymentImplement $paymentImplement)
    {
        $this->categoryImplement = $categoryImplement;
        $this->countryImplement = $countryImplement;
        $this->paymentImplement = $paymentImplement;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $title = "Payment List";

            $data = $this->paymentImplement->getAll();
            $category = $this->categoryImplement->getAll();
            $country = $this->countryImplement->getAll();

            return view('cms.pages.payment.index', compact('title', 'data', 'country', 'category'));
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        try {

            Payment::create([
                'payment_id' => Str::uuid(),
                'category_id' => $request->category,
                'country_id' => $request->country,
                'channel_id' => $request->channel_id,
                'name_channel' => $request->name,
                'logo_channel' => $this->_upload($request->file('thumbnail'))
            ]);

            $notif = array(
                'message' => 'Success Create Payment',
                'alert-info' => 'success'
            );

            return redirect()->back()->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentUpdateRequest $request)
    {
        try {
            $payment = $this->paymentImplement->getId($request->id);
            if (!$payment) {
                $notif = array(
                    'message' => 'Update Payment Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };


            $this->_remove($request, $payment);

            Payment::where('payment_id', $request->id)->update([
                'category_id' => $request->category,
                'country_id' => $request->country,
                'channel_id' => $request->channel_id,
                'name_channel' => $request->name,
                'logo_channel' => (!$request->file('thumbnail')) ? $payment->first()->logo_channel : $this->_upload($request->file('thumbnail'))
            ]);

            $notif = array(
                'message' => 'Success Update Payment',
                'alert-info' => 'success'
            );

            return redirect()->back()->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $payment = $this->paymentImplement->getId($request->id);
            if (!$payment) {
                $notif = array(
                    'message' => 'Delete Payment Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };



            File::delete('image/' .  $payment->logo_channel);

            $this->paymentImplement->delete($request->id);

            $notif = array(
                'message' => 'Success Delete Payment',
                'alert-info' => 'success'
            );

            return redirect()->back()->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }
}
