<?php

namespace App\Http\Controllers\cms;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\User\UserImplement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

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
    }

    public function register()
    {
        $title = "Register Page";
        return view('cms.pages.auth.register', compact('title'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->errors());
        };

        $this->userImplement->Create($request);

        $notif = array(
            'message' => 'Success Register',
            'alert-info' => 'success'
        );
        return redirect()->route('auth.login')->with($notif);
    }

    // fungsi logout
    public function logout()
    {
        auth()->logout();
        $notif = array(
            'message' => 'Success Log out',
            'alert-info' => 'success'
        );
        return redirect()->route('auth.login')->with($notif);
    }
}
