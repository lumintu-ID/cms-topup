<?php

namespace App\Http\Controllers\cms;

use App\Models\Price;
use App\Models\Country;
use App\Models\Payment;
use App\Models\GameList;
use App\Models\PricePoint;
use Illuminate\Support\Str;
use App\Imports\PriceImport;
use Illuminate\Http\Request;
use App\Http\Requests\PriceRequest;
use App\Http\Controllers\Controller;
use App\Models\ppi_list;
use Maatwebsite\Excel\Facades\Excel;
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

        return view('cms.pages.price.index', compact('game', 'payment', 'ppi',  'data', 'title'));
    }


    public function add()
    {
        $title = "Add Price";
        $game = GameList::all();
        $payment = Payment::all();
        $ppi = PricePoint::all();
        $country = Country::all();
        return view('cms.pages.price.add', compact('game', 'payment', 'ppi', 'country',  'title'));
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
            // dd($request);


            // cek game


            // cek payment

            // set new data to save in database

            $newData = [];

            for ($i = 0; $i < count($request->ppi); $i++) {
                $ppi = PricePoint::where('id', $request->ppi[$i])->first();

                $result = [
                    'game_id' => $request->game,
                    'payment_id' => $request->payment,
                    'name' => $request->name,
                    'price_point_id' => $ppi->id,
                    'amount' => ($request->amount[$i] == null) ? $ppi->amount : $request->amount[$i],
                    'price' => $request->price[$i] == null ? $ppi->price : $request->price[$i],
                ];

                \array_push($newData, $result);
            };


            $this->priceImplement->Create($newData);

            $notif = array(
                'message' => 'Success Create Price',
                'alert-info' => 'success'
            );

            return redirect(route('cms.price'))->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }


    public function addAll(Request $request)
    {
        // dd($request);

        try {
            // cek game

            $ppi = ppi_list::with('pricepoint')->where('game_id', $request->game)->get();


            // cek payment

            // set new data to save in database

            $newData = [];



            for ($i = 0; $i < count($ppi); $i++) {

                $result = [
                    'game_id' => $request->game,
                    'payment_id' => $request->payment,
                    'name' => $request->name,
                    'price_point_id' => $ppi[$i]->price_point_id,
                    'amount' => $ppi[$i]->pricepoint->amount,
                    'price' => $ppi[$i]->pricepoint->price,
                ];

                \array_push($newData, $result);
            };

            $this->priceImplement->Create($newData);

            $notif = array(
                'message' => 'Success Create Price',
                'alert-info' => 'success'
            );

            return redirect(route('cms.price'))->with($notif);
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


    public function import(Request $request)
    {
        try {
            Excel::import(new PriceImport, request()->file('file'));

            $notif = array(
                'message' => 'Success Import Price',
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
