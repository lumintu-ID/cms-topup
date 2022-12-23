<?php

namespace App\Http\Controllers\cms;

use App\Models\User;
use App\Models\user_role;
use App\Models\navigation;
use App\Models\user_access;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "List User Access";
        $data = user_role::orderBy('created_at', 'asc')->get();
        return view('cms.pages.user-access.index', compact('title', 'data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            user_role::create([
                'role'      => $request->role,
            ]);

            $notif = array(
                'message' => 'Success Create Role',
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
    public function update(Request $request)
    {
        try {
            $role = user_role::where('role_id', $request->id);

            if (!$role->get()) {
                $notif = array(
                    'message' => 'Update user Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $role->update([
                'role'      => $request->role,
            ]);

            $notif = array(
                'message' => 'Success Update Role',
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $role = user_role::where('role_id', $request->id);

            if (!$role->get()) {
                $notif = array(
                    'message' => 'Delete user Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $check = User::where('role_id', $request->id)->first();

            if ($check) {
                $notif = array(
                    'message' => 'Delete user Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            }


            $role->delete();

            $notif = array(
                'message' => 'Success Delete Role',
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


    public function access($id = null)
    {
        try {
            $title = "List User Access";
            $data = navigation::orderBy('id_label', 'asc')->get();

            $role_id = base64_decode($id);
            $role = user_role::where('role_id', $role_id)->first();

            if (!$role) {
                $notif = array(
                    'message' => 'Role not Found',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            }

            return view('cms.pages.user-access.access', compact('title', 'data', 'role'));
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }

    public function checked(Request $request)
    {
        try {
            $checkAccess = user_access::where('nav_id', $request->nav)
                ->where('role_id', $request->role)
                ->first();


            if (!$checkAccess) {
                user_access::create([
                    'access_id' => Str::uuid(),
                    'role_id'   => $request->role,
                    'nav_id'    => $request->nav
                ]);


                return 'Access ' . $request->name . ' Enable';
            } else {
                user_access::where('nav_id', $request->nav)
                    ->where('role_id', $request->role)
                    ->delete();


                return 'Access ' . $request->name . ' Disable';
            }
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }
}
