<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\GameList;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class HomeController extends Controller
{
    public function index()
    {   
        $games = GameList::all();
        return view('frontend.home.index', compact('games'));
    }
}
