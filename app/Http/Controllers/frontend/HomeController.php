<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Repository\Frontend\GeneralRepository;

class HomeController extends Controller
{
    public function __construct(GeneralRepository $generalRepository)
    {
        $this->generalRepository = $generalRepository;
    }

    public function index()
    {   
        $games = $this->generalRepository->getAllDataGame();

        return view('frontend.home.index', compact('games'));
    }
}
