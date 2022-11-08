<?php

namespace App\Http\Controllers\api;

use App\Models\Price;
use App\Models\Payment;
use App\Models\GameList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repository\Api\ApiImplement;

class PaymentController extends Controller
{

    protected $apiImplement;

    public function __construct(ApiImplement $apiImplement)
    {
        $this->apiImplement = $apiImplement;
    }

    public function index(Request $request)
    {
        try {
            $country = $request->query('country');
            $game = $request->query('game_id');

            $dataGame = GameList::where('id', $game)->first();

            $payment = Payment::where('country_id', $country)->get();

            if ($dataGame == null || count($payment) == 0) {
                Log::warning('Data get Payment Not Found', ["Date" => date(now())]);
                return response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Not Found',
                ], 404);
            };

            $result = $this->apiImplement->priceList($payment, $dataGame->id);


            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Get Game List',
                'data' => $result
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {


            Log::critical('Critical error', ['Path' => request()->server('PATH_INFO')]);
            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
