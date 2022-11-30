<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home.index');
    }
}
