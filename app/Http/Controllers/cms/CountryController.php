<?php

namespace App\Http\Controllers\cms;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Repository\Country\CountryImplement;

class CountryController extends Controller
{

    protected $countryImplement;
    public function __construct(CountryImplement $countryImplement)
    {
        $this->countryImplement = $countryImplement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Country List";

        $data = $this->countryImplement->getAll();

        return view('cms.pages.country.index', compact('title', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        try {

            $this->countryImplement->create($request);

            $notif = array(
                'message' => 'Success Create Country',
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
    public function update(CountryRequest $request)
    {
        try {
            $country = $this->countryImplement->getId($request->id);

            if (!$country) {
                $notif = array(
                    'message' => 'Update Country Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->countryImplement->update($request->id, $request);

            $notif = array(
                'message' => 'Success Update Country',
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
            $country = $this->countryImplement->getId($request->id);

            if (!$country) {
                $notif = array(
                    'message' => 'Delete Country Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->countryImplement->delete($request->id);

            $notif = array(
                'message' => 'Success Delete Country',
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
