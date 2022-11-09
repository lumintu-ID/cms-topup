<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Country;

use App\Repository\Api\ApiImplement;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{

    protected $apiImplement;

    public function __construct(ApiImplement $apiImplement)
    {
        $this->apiImplement = $apiImplement;
    }

    public function index()
    {
        try {
            $data = $this->apiImplement->getGameList();



            if (!$data) {
                Log::warning('Data Game List Not Found', ["Date" => date(now())]);
                return response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Game List Not Found',
                ], 404);
            };


            $result = [];

            foreach ($data as $game) {

                $gm = array(
                    'id' => $game->id,
                    'game_id' => $game->game_id,
                    'game_title' => $game->game_title,
                    'cover' => url('/cover/' . $game->cover),
                    'created_at' => $game->created_at,
                    'updated_at' => $game->updated_at
                );


                array_push($result, $gm);
            }



            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Get Game List',
                'data' => $result
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error Get Data Game', ["Date" => date(now())]);
            Log::critical('Critical error', ['Path' => request()->server('PATH_INFO')]);

            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function gameDetail(Request $request)
    {
        try {
            $game_id = $request->query('game');
            $game = $this->apiImplement->gameDetail($game_id);
            $country = Country::get();

            if (!$game) {
                Log::warning('Data Game List Not Found', ["Date" => date(now())]);
                return \response()->json([
                    'code' => 404,
                    'status' => 'NOT_FOUND',
                    'error' => 'Data Game List Not Found',
                ], 404);
            };


            $gm = array(
                'id' => $game['id'],
                'game_id' => $game['game_id'],
                'game_title' => $game['game_title'],
                'cover' => url('/cover/' . $game['cover']),
                'created_at' => $game['created_at'],
                'updated_at' => $game['updated_at']
            );




            $result = array(
                'game_detail' => $gm,
                'country_list' => $country
            );



            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Get Game List',
                'data' => $result
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error Get Data Game', ["Date" => date(now())]);
            Log::critical('Critical error', ['Path' => request()->server('PATH_INFO')]);

            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
