<?php

namespace App\Http\Controllers\cms;

use App\Models\GameList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\GameRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameUpdateRequest;
use Illuminate\Support\Facades\File;

class GameController extends Controller
{

    private function _upload($file)
    {
        $name_file = time() . "_" . $file->getClientOriginalName();
        $dir = 'cover/';
        $file->move($dir, $name_file);

        return $name_file;
    }

    private function _remove($file, $data)
    {
        if ($file->file('cover')) {
            File::delete('cover/' . $data);
            return;
        };

        return;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Game List";

        $data = GameList::all();

        return view('cms.pages.game.index', compact('title', 'data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameRequest $request)
    {
        try {
            GameList::create([
                'id' => Str::uuid(),
                'game_id' => $request->game_id,
                'game_title' => $request->game_title,
                'cover' => $this->_upload($request->file('cover'))
            ]);

            $notif = array(
                'message' => 'Success Create Game List',
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
    public function update(GameUpdateRequest $request)
    {

        // try {
        $game = GameList::where('id', $request->id)->first();

        if (!$game) {
            $notif = array(
                'message' => 'Update Game Failed',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        };


        if ($request->file('cover')) {
            $this->_remove($request, $game->cover);
        };


        GameList::where('id', $request->id)->update([
            'id' => Str::uuid(),
            'game_id' => $request->game_id,
            'game_title' => $request->game_title,
            'cover' => (!$request->file('cover')) ? $game->cover : $this->_upload($request->file('cover'))
        ]);

        $notif = array(
            'message' => 'Success Update Game',
            'alert-info' => 'success'
        );

        return redirect()->back()->with($notif);
        // } catch (\Throwable $th) {
        //     $notif = array(
        //         'message' => 'Internal Server Error',
        //         'alert-info' => 'warning'
        //     );

        //     return redirect()->back()->with($notif);
        // }
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
            $game = GameList::where('id', $request->id)->first();

            if (!$game) {
                $notif = array(
                    'message' => 'Delete Game Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };



            File::delete('cover/' .  $game->cover);

            GameList::where('id', $request->id)->delete($request->id);

            $notif = array(
                'message' => 'Success Delete Game',
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
