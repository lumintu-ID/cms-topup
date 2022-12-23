<?php

namespace App\Http\Controllers\cms;

use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{

    private function _upload($file)
    {
        $name_file = time() . "_" . $file->getClientOriginalName();
        $dir = 'banner/';
        $file->move($dir, $name_file);

        return $name_file;
    }

    private function _remove($file, $data)
    {
        if ($file->file('banner')) {
            File::delete('banner/' . $data);
            return;
        };

        return;
    }

    public function index()
    {
        try {
            $title = "Banner List";
            $data = Banner::all();
            return view('cms.pages.banner.index', compact('title', 'data'));
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }

    public function store(Request $request)
    {
        try {
            Banner::create([
                'id_banner' => Str::uuid(),
                'banner' => $this->_upload($request->file('banner')),
                'is_active' => 1
            ]);

            $notif = array(
                'message' => 'Success Create Banner',
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


    public function update(Request $request)
    {
        try {
            $banner = Banner::where('id_banner', $request->id);

            if (!$banner->first()) {
                $notif = array(
                    'message' => 'Update Banner Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $bn = $banner->first();

            if ($request->file('banner')) {
                $this->_remove($request, $bn->banner);
            };

            $banner->update([
                'banner' => (!$request->file('banner')) ? $bn->banner : $this->_upload($request->file('banner'))
            ]);

            $notif = array(
                'message' => 'Success Update Banner',
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
            $banner = Banner::where('id_banner', $request->id);

            if (!$banner->first()) {
                $notif = array(
                    'message' => 'Update Banner Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $bn = $banner->first();


            File::delete('banner/' .  $bn->banner);

            Banner::where('id_banner', $request->id)->delete($request->id);

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


    public function changeStatus($id)
    {
        try {
            $banner = Banner::where('id_banner', $id);

            if (!$banner->first()) {
                $notif = array(
                    'message' => 'Update Banner Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $bn = $banner->first();


            $banner->where('id_banner', $id)->update([
                'is_active' => ($bn->is_active == 0) ? 1 : 0,
            ]);

            $notif = array(
                'message' => 'Success Change Status Banner',
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
