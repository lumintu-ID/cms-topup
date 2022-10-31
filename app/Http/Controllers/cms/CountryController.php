<?php

namespace App\Http\Controllers\cms;

use App\Models\Country;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Country List";

        $data = Country::all();

        return view('cms.pages.country.index', compact('title', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'currency' => 'required|string',
            'country' => 'required|string'
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        };

        Country::create([
            'country_id' => Str::uuid(),
            'currency' => $request->currency,
            'country' => $request->country
        ]);

        $notif = array(
            'message' => 'Success Create Country',
            'alert-info' => 'success'
        );

        return redirect()->back()->with($notif);
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
        $country = Country::where('country_id', $request->id);

        if (!$country->first()) {
            $notif = array(
                'message' => 'Update Country Failed',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        };

        $country->update([
            'currency' => $request->currency,
            'country' => $request->country
        ]);

        $notif = array(
            'message' => 'Success Update Country',
            'alert-info' => 'success'
        );

        return redirect()->back()->with($notif);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $country = Country::where('country_id', $request->id);

        if (!$country->first()) {
            $notif = array(
                'message' => 'Delete Country Failed',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        };

        $country->delete();

        $notif = array(
            'message' => 'Success Delete Country',
            'alert-info' => 'success'
        );

        return redirect()->back()->with($notif);
    }
}
