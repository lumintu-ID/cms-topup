<?php

namespace App\Http\Controllers\cms;

use App\Models\Country;
use App\Models\Payment;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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
            File::delete('image/' . $data->first()->logo_channel);
            return;
        };

        return;
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

            $data = Payment::with('category', 'country')->orderBy('category_id')->get();
            $category = Category::all();
            $country = Country::all();

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
    public function store(Request $request)
    {
        try {
            $valid = Validator::make($request->all(), [
                'name' => 'required|string',
                'category' => 'required',
                'country' => 'required',
                'channel_id' => 'required',
                'thumbnail' => 'required|file|image|mimes:jpeg,png,jpg|max:1048'
            ]);

            if ($valid->fails()) {
                return redirect()->back()->withInput()->withErrors($valid->errors());
            };

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
    public function update(Request $request)
    {
        try {
            $payment = Payment::where('payment_id', $request->id);
            if (!$payment->first()) {
                $notif = array(
                    'message' => 'Update Payment Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };


            $this->_remove($request, $payment);

            $payment->update([
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
            $payment = Payment::where('payment_id', $request->id);
            if (!$payment->first()) {
                $notif = array(
                    'message' => 'Delete Payment Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };



            File::delete('image/' .  $payment->first()->logo_channel);

            $payment->delete();

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
