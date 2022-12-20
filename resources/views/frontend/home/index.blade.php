@extends('frontend.layouts.app')

@section('content')
  <section class="header__hero">
    @include('frontend.home._hero-section')
  </section>
  <section class="section-games container-lg">
    <div class="games__header">
      <div class="games-header__title">Voucher Game</div>
    </div>
    <div class="row gy-4 gy-md-5">
      @include('frontend.home._card-games')
    </div>
    <div class="row justify-content-center mt-3 py-5 ">
      <a href="list-game.html" class="button__primary">
        Game Lainnya
      </a>
    </div>
  </section>
  <section class="promo-info container-lg">
    <div class="row">
      <div class="promo-info__header">
        <div class="promo-info-header__title">
          Promosi & Berita
        </div>
      </div>
      <div class="promo-info__body">
        <div class="row row-cols-1 gy-5">
          @include('frontend.home._info-video')
          @include('frontend.home._info-news')
        </div>
        <div class="row justify-content-center mt-5 py-3">
          <a href="#" class="button__primary">
            Lihat Semua
          </a>
        </div>
      </div>
    </div>
  </section>
  <section class="support container-fluid container-lg">
    <div class="support__header">
      <div class="support-header__title">Dukungan</div>
    </div>
    <div class="row row-cols-1 row-cols-sm-2 gy-3">
      <div class="col">
        <div class="row row-cols-2 row-cols-sm-2 g-3">
          @include('frontend.home._medsos')
        </div>
      </div>
      <div class="col">
        <div class="support__hero"></div>
      </div>
    </div>
  </section>

@endsection