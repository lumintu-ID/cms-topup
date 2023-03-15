<?php

namespace App\Repository\Frontend;

use App\Models\Banner;
use App\Models\Country;
use App\Models\GameList;

class GeneralRepositoryImplement implements GeneralRepository
{
  public function getAllDataGame()
  {
    return GameList::select(
      'slug_game',
      'game_title',
      'cover'
    )->get();
  }

  public function getDataGameById(string $id)
  {
    return GameList::select(
      'id',
      'game_id as code_game',
      'game_title as title',
      'cover'
    )->where('id', $id)->first();
  }

  public function getDataGameBySlug(string $slug)
  {
    return GameList::select(
      'id',
      'game_id as code_game',
      'game_title as title',
      'cover'
    )->where('slug_game', $slug)->first();
  }

  public function getAllDataCountry()
  {
    return Country::select(
      'country_id as id',
      'country',
      'currency'
    )->get()->toArray();
  }

  public function getAllBanner(int $limit = 5)
  {
    $data = Banner::select(
      'id_banner as id',
      'banner as name',
    )->get();
  }
}
