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

        // Http::asForm()->post('https://pay.goc.id/', [
        //     "merchantId" => "Esp5373790",
        //     "trxId" => "454126606313320104",
        //     "trxDateTime" => "2022-12-07T07:51:48+07",
        //     "channelId" => 63,
        //     "amount" => 5000,
        //     "currency" => "IDR",
        //     "returnUrl" => "http://127.0.0.1:8000/",
        //     "userId" => "647762653360703474",
        //     "phone" => "081240157378",
        //     "sign" => '807b6e0ccf27513263944443d3f038efe8410a44f0672fb85b5fac75acba495c',
            
        // ]);

        return 'Do Checkout';
    }

    public function getListPayment(Request $request)
    {
        $countryId = 'f1f834b4-8b02-431c-a8cd-493e0944b5e2';
        $payment = Payment::select('payment_id', 'category_id', 'country_id', 'channel_id', 'name_channel', 'logo_channel', 'url')->where('country_id', $countryId)->first()->makeHidden(['created_at','updated_at' ]);

        dd($payment);

        return ;
    }

    public function generate(Request $request)
    {   
        $data = [
            "merchantId" => "Esp5373790",
            "trxId" => "454126606313320104",
            "trxDateTime" => "2022-12-07T07:51:48+07",
            "returnUrl" => "http://127.0.0.1:8000/",
            "sign" => '807b6e0ccf27513263944443d3f038efe8410a44f0672fb85b5fac75acba495c',
        ];
        $dataGenerate[] = $data;
        return response()->json($dataGenerate);
    }
}


