<?php

namespace App\Http\Controllers\cms;

use App\Models\PricePoint;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PricePointRequest;

class PricePointController extends Controller
{
    public function index()
    {
        $title = 'Price Point List';

        $data = PricePoint::all();

        return view('cms.pages.pricepoint.index', compact('title', 'data'));
    }


    public function store(PricePointRequest $request)
    {

        try {
            PricePoint::create([
                'id_price_point' => Str::uuid(),
                'price_point' => $request->price_point
            ]);

            $notif = array(
                'message' => 'Success Create Price Point ID',
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
    public function update(PricePointRequest $request)
    {
        try {
            $pricepoint = PricePoint::where('id_price_point', $request->id)->get();

            if (count($pricepoint) <= 0) {
                $notif = array(
                    'message' => 'Update Price Point ID Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            PricePoint::where('id_price_point', $request->id)->update([
                'price_point' => $request->price_point
            ]);

            $notif = array(
                'message' => 'Success Update Price Point ID',
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
            $pricepoint = PricePoint::where('id_price_point', $request->id)->get();

            if (count($pricepoint) <= 0) {
                $notif = array(
                    'message' => 'Update Price Point ID Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            PricePoint::where('id_price_point', $request->id)->delete();

            $notif = array(
                'message' => 'Success Delete Price Point ID',
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
