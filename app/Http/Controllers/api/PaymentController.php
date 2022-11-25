<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
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
                Log::warning('Get Payment List Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | id game ' . $game . ' and country id ' . $country . ' not found']);

                return response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Not Found',
                ], 404);
            };

            $result = $this->apiImplement->priceList($payment, $dataGame->id);

            Log::info('Success Get Payment List', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Get Payment List with id game ' . $game . ' and country id ' . $country]);

            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Get Game List',
                'data' => $result
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error Get Payment List', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Get Payment List']);
            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAllPayment()
    {
        try {

            $payment = Payment::select('payment_id', 'name_channel')->get();

            if (count($payment) == 0) {
                Log::warning('Get Payment List Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | data not found ']);

                return response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Not Found',
                ], 404);
            };


            Log::info('Success Get Payment List', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Get all payment']);

            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Get Payment List',
                'data' => $payment
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error Get Payment List', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Get Payment List']);
            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
