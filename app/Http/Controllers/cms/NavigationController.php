<?php

namespace App\Http\Controllers\cms;

use App\Models\navigation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationRequest;
use App\Repository\Navigation\NavigationImplement;

class NavigationController extends Controller
{

    protected $navigationImplement;

    public function __construct(NavigationImplement $navigationImplement)
    {
        $this->navigationImplement = $navigationImplement;
    }

    public function index()
    {
        $title = "List Navigation";
        $data = $this->navigationImplement->getAll();
        return \view('cms.pages.navigation.index', \compact('title', 'data'));
    }

    public function store(NavigationRequest $request)
    {

        try {
            $this->navigationImplement->create($request);

            $notif = array(
                'message' => 'Success Create Navigation',
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

    public function update(NavigationRequest $request)
    {
        try {
            $navigation = $this->navigationImplement->getId($request->id);

            if (!$navigation) {
                $notif = array(
                    'message' => 'Update Navigation Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->navigationImplement->update($request->id, $request);

            $notif = array(
                'message' => 'Success Create Navigation',
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

    public function changeStatus($id)
    {
        try {
            $navigation = $this->navigationImplement->getId($id);

            if (!$navigation) {
                $notif = array(
                    'message' => 'Update Navigation Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };


            $this->navigationImplement->updateStatus($id, $navigation);

            $notif = array(
                'message' => 'Success Change Status Navigation',
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

    public function destroy(Request $request)
    {
        try {
            $navigation = $this->navigationImplement->getId($request->id);

            if (!$navigation) {
                $notif = array(
                    'message' => 'Navigation Not Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->navigationImplement->delete($request->id);

            $notif = array(
                'message' => 'Success Delete Navigation',
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
