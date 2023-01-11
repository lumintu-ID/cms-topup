<?php

namespace App\Repository\Frontend;

interface GeneralRepository
{
  public function getAllDataGame();
  public function getDataGameBySlug(string $id);
  public function getAllDataCountry();
  public function getAllBanner();
}