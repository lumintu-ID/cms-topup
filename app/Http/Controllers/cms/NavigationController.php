<?php

namespace App\Http\Controllers\cms;

use App\Models\navigation;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationRequest;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index()
    {
        $title = "List Navigation";
        $data = navigation::orderBy('id_label', 'asc')->get();
        return \view('cms.pages.navigation.index', \compact('title', 'data'));
    }

    public function store(NavigationRequest $request)
    {

        try {
            navigation::create([
                'nav_id'        => Str::uuid(),
                'id_label'      => $request->label,
                'url'           => $request->url,
                'navigation'    => $request->name,
                'icon'          => $request->icon,
                'is_active'     => 1
            ]);

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
            $navigation = navigation::where('nav_id', $request->id);

            if (!$navigation->get()) {
                $notif = array(
                    'message' => 'Update Navigation Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $navigation->update([
                'id_label'      => $request->label,
                'url'           => $request->url,
                'navigation'    => $request->name,
                'icon'          => $request->icon,
            ]);

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
            $navigation = navigation::where('nav_id', $id);

            if (!$navigation->get()) {
                $notif = array(
                    'message' => 'Update Navigation Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };


            $check = $navigation->first();


            $navigation->update([
                'is_active' => ($check->is_active == 0) ? 1 : 0,
            ]);

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
            $navigation = navigation::where('nav_id', $request->id);

            if (!$navigation->get()) {
                $notif = array(
                    'message' => 'Navigation Not Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $navigation->delete();

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
