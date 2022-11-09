<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Country;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repository\Api\ApiImplement;

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
                Log::warning('Get Game List Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | Data Game List not found']);
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

            Log::info('Success Get Game List', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Get Data Game List']);
            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Get Game List',
                'data' => $result
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error Get Game List', ["Date" => Carbon::now()->format('Y-m-d H:i:s')]);

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
                Log::warning('Detail Game Not Found', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | WARN ' . ' | Game id ' . $game_id . ' not found']);
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

            Log::info('Success Get Detail Game', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | INFO ' . ' | Success Get Detail Game with id ' . $game_id]);

            return \response()->json([
                'code' => Response::HTTP_OK,
                'status' => 'OK',
                'message' => 'Success Get Game List',
                'data' => $result
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error Get Detail Game', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Get Detail Game']);

            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
