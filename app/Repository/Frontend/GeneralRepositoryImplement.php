<?php

namespace App\Repository\Frontend;

use App\Models\Country;
use App\Models\GameList;

class GeneralRepositoryImplement implements GeneralRepository
{
  public function getAllDataGame()
  {
    return;
  }
  
  public function getDataGameById(string $id)
  {
    $data = GameList::select(
      'id',
      'game_id as code_game',
      'game_title as title',
      'cover')
    ->where('id', $id)->first();
    
    return $data;
  }

  public function getDataGameBySlug(string $slug)
  {
    $data = GameList::select(
      'id',
      'game_id as code_game',
      'game_title as title',
      'cover')
    ->where('slug_game', $slug)->first();
    
    return $data;
  }

  public function getAllDataCountry()
  {
    $data = Country::select(
      'country_id as id',
      'country',
      'currency')
    ->get()->toArray();
    
    return $data;
  }
}