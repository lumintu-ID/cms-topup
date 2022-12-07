<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\GameList;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {   
        $slug = $request->slug;
        $dataGame = GameList::select('id', 'game_id', 'game_title', 'cover')->where('slug_game', $slug)->first();
        
        $countries = Country::all();

        return view('frontend.payment.index', compact('countries', 'dataGame'));
    }

    public function doCheckout(Request $request) 
    {
        dd($request->input());
        return 'do checkout';
    }

    public function getListPayment(Request $request)
    {
        $countryId = 'f1f834b4-8b02-431c-a8cd-493e0944b5e2';
        $payment = Payment::select('payment_id', 'category_id', 'country_id', 'channel_id', 'name_channel', 'logo_channel', 'url')->where('country_id', $countryId)->first()->makeHidden(['created_at','updated_at' ]);

        dd($payment);

        return ;
    }
}


