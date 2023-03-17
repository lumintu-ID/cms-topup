<?php

namespace App\Http\Controllers\cms;

use App\Models\Country;
use App\Models\GameList;
use App\Models\PricePoint;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PricePointRequest;
use App\Models\ppi_list;

class PricePointController extends Controller
{
    public function index()
    {
        $title = 'Price Point List';
        $game = GameList::all();

        return view('cms.pages.pricepoint.game', compact('title', 'game'));
    }

    public function list($id = null)
    {
        $game = GameList::find($id);

        if (!$game) {
            $notif = array(
                'message' => 'Game Not Found',
                'alert-info' => 'warning'
            );

            return redirect(route('cms.pricepoint'))->with($notif);
        }

        $ppilist = ppi_list::where('game_id', $id)->get();

        $data = [];

        foreach ($ppilist as $ppi) {
            $result = PricePoint::where('id', $ppi->price_point_id)->with('country')->first();

            // $newResult = [
            //     'id' => $result->id,
            //     'country_id' => $result->country_id,
            //     'price_point_id' => $result->price_point,
            //     'amount' => $result->amount,
            //     'price' => $result->price,
            //     "country" => $result->country->country,
            //     "currency" => $result->country->currency
            // ];

            \array_push($data, $result);
        };

        $title = 'Price Point List - ' . $game->game_title;
        $game = $id;
        $country = Country::all();

        return view('cms.pages.pricepoint.index', compact('title', 'data', 'country', 'game'));
    }

    public function add()
    {
        $title = "Add Price Point";
        $game = GameList::all();
        $country = Country::all();
        return view('cms.pages.pricepoint.add', compact('game', 'country',  'title'));
    }


    public function store(PricePointRequest $request)
    {

        try {

            $game = GameList::find($request->game);
            if (!$game) {
                $notif = array(
                    'message' => 'Game Not Found',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            }


            $country = Country::where('currency', $request->country)->first();
            if (!$country) {
                $notif = array(
                    'message' => 'Country Not Found',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            }


            if (is_array($request->price_point)) {
                for ($i = 0; $i < count($request->price_point); $i++) {

                    $ppi_id = Str::uuid();

                    ppi_list::create([
                        'game_id' => $request->game,
                        'price_point_id' => $ppi_id
                    ]);


                    PricePoint::create([
                        'id' => $ppi_id,
                        'price_point' => $request->price_point[$i],
                        'country_id' => $request->country,
                        'amount' => $request->amount[$i],
                        'price' => $request->price[$i]
                    ]);
                }
            } else {
                $ppi_id = Str::uuid();

                ppi_list::create([
                    'game_id' => $request->game,
                    'price_point_id' => $ppi_id
                ]);


                PricePoint::create([
                    'id' => $ppi_id,
                    'price_point' => $request->price_point,
                    'country_id' => $request->country,
                    'amount' => $request->amount,
                    'price' => $request->price
                ]);
            }



            $notif = array(
                'message' => 'Success Create Price Point ID',
                'alert-info' => 'success'
            );

            return redirect(route('cms.pricepoint.list', $request->game))->with($notif);
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
            $pricepoint = PricePoint::where('id', $request->id)->get();

            if (count($pricepoint) <= 0) {
                $notif = array(
                    'message' => 'Update Price Point ID Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            PricePoint::where('id', $request->id)->update([
                'price_point' => $request->price_point,
                'country_id' => $request->country,
                'amount' => $request->amount,
                'price' => $request->price
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
            $pricepoint = PricePoint::where('id', $request->id)->get();

            if (count($pricepoint) <= 0) {
                $notif = array(
                    'message' => 'Delete Price Point ID Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            PricePoint::where('id', $request->id)->delete();
            ppi_list::where('price_point_id', $request->id)->delete();

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
