<?php

namespace App\Repository\Frontend;

use App\Models\Banner;
use App\Models\Country;
use App\Models\GameList;

class GeneralRepositoryImplement implements GeneralRepository
{
  public function getAllDataGame()
  {
    $data = GameList::select(
      'slug_game',
      'game_title',
      'cover')
    ->get();
    return $data;
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

  public function getAllBanner(int $limit = 5)
  {
    $data = Banner::select(
      'id_banner as id',
      'banner as name',
    )->get();

    return $data;
  }
  

}