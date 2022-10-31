<?php

namespace App\Http\Controllers\cms;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\user_role;
use App\Repository\User\UserImplement;

class UserController extends Controller
{

    protected $userImplement;

    public function __construct(UserImplement $userImplement)
    {
        $this->userImplement = $userImplement;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "List User";
        $role = user_role::orderBy('created_at', 'asc')->get();
        $data = $this->userImplement->getAll();
        return view('cms.pages.user.index', compact('title', 'data', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $this->userImplement->Create($request->all());

            $notif = array(
                'message' => 'Success Create User',
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
    public function update(UserRequest $request)
    {
        try {
            $user = $this->userImplement->getId($request->id);

            if (!$user) {
                $notif = array(
                    'message' => 'Update user Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };


            $this->userImplement->update($request->id, $request);

            $notif = array(
                'message' => 'Success Update user',
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
            $user = $this->userImplement->getId($id);

            if (!$user) {
                $notif = array(
                    'message' => 'Update user Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };


            User::where('user_id', $id)->update([
                'is_active' => ($user->is_active == 0) ? 1 : 0,
            ]);

            $notif = array(
                'message' => 'Success Change Status user',
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
            $user = $this->userImplement->getId($request->id);

            if (!$user) {
                $notif = array(
                    'message' => 'Update user Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->userImplement->delete($request->id);

            $notif = array(
                'message' => 'Success Delete User',
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
