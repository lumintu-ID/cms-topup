<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Repository\Frontend\GeneralRepository;
use Illuminate\Http\Request;

class GameController extends Controller
{
    private $activeLink = 'games';
    private $_generalRepository;

    public function __construct(GeneralRepository $generalRepository)
    {
        $this->_generalRepository = $generalRepository;
    }

    public function index()
    {
        $games = $this->_generalRepository->getAllDataGame();
        $activeLink = $this->activeLink;
        return view('frontend.game.index', compact('games', 'activeLink'));
    }
}
