@extends('frontend.layouts.app')
@section('content')
<section class="section-banner py-2 py-md-4">
  @include('frontend.home._hero-section')
</section>
<section class="section-games mt-3" id="games">
  <div class="row">
    <div class="label-section">Game Voucher</div>
  </div>
  <div class="row gy-4 gy-md-5 py-3 py-lg-4">
    @include('frontend._component.card-games')
  </div>
  <div class="row justify-content-center mt-2 py-5 px-3">
    <a href="{{ route('games') }}" class="button__primary">
      All Games
    </a>
  </div>
</section>
<section class="section-promo-info mt-3" id="news">
  <div class="row">
    <div class="label-section">News Info</div>
  </div>
  <div class="row row-cols-1 gy-4 py-3 py-lg-4">
    @include('frontend.home._info-video')
    @include('frontend.home._info-news')
  </div>
  <div class="row justify-content-center mt-3 py-5 px-3">
    <a href="#" class="button__primary">
      other news
    </a>
  </div>
</section>
@endsection