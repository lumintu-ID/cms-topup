<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repository\User\UserImplement;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    protected $userImplement;

    function __construct(UserImplement $userImplement)
    {

        $this->userImplement = $userImplement;


        if (Auth::user() && Auth::user()->is_active == 1) {
            $notif = array(
                'message' => 'Opps Login First',
                'alert-info' => 'warning'
            );
            return \redirect()->route('auth.login');
        };
    }
    public function index()
    {
        $title = "Login Page";
        return view('cms.pages.auth.login', compact('title'));
    }

    public function login(LoginRequest $request)
    {
        try {
            // cek apakah email dan password benar
            if (Auth::attempt(request(['email', 'password']))) {

                $notif = array(
                    'message' => 'Success Login',
                    'alert-info' => 'success'
                );
                return redirect()->route('cms.dashboard')->with($notif);
            }

            $notif = array(
                'message' => 'Email or password not match',
                'alert-info' => 'warning'
            );
            // jika salah, kembali ke halaman login
            return redirect()->back()->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        };
    }

    public function register()
    {
        $title = "Register Page";
        return view('cms.pages.auth.register', compact('title'));
    }

    public function store(RegisterRequest $request)
    {
        try {

            $this->userImplement->Create($request);

            $notif = array(
                'message' => 'Success Register',
                'alert-info' => 'success'
            );
            return redirect()->route('auth.login')->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        };
    }

    // fungsi logout
    public function logout()
    {
        try {
            auth()->logout();
            $notif = array(
                'message' => 'Success Log out',
                'alert-info' => 'success'
            );
            return redirect()->route('auth.login')->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        };
    }
}
