<?php

namespace App\Http\Controllers\cms;

use App\Models\Price;
use App\Models\Payment;
use App\Models\GameList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\PriceRequest;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PricePoint;
use App\Repository\Price\PriceImplement;

class PriceController extends Controller
{

    protected $priceImplement;
    public function __construct(PriceImplement $priceImplement)
    {
        $this->priceImplement = $priceImplement;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Price List";

        $data = $this->priceImplement->getAll();

        $game = GameList::all();
        $payment = Payment::all();
        $ppi = PricePoint::all();
        $country = Country::all();

        return view('cms.pages.price.index', compact('game', 'payment', 'ppi', 'country', 'data', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceRequest $request)
    {
        try {
            // dd($request);
            $this->priceImplement->Create($request);

            $notif = array(
                'message' => 'Success Create Price',
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
    public function update(PriceRequest $request)
    {
        try {
            $price = $this->priceImplement->getId($request->id);

            if (!$price) {
                $notif = array(
                    'message' => 'Update Price Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->priceImplement->update($request->id, $request);

            $notif = array(
                'message' => 'Success Update Price',
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
            $price = $this->priceImplement->getId($request->id);

            if (!$price) {
                $notif = array(
                    'message' => 'Update Price Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->priceImplement->delete($request->id);

            $notif = array(
                'message' => 'Success Delete Price',
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
