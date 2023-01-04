@extends('frontend.layouts.app')

@section('content')
<section class="section-banner">
  @include('frontend.home._hero-section')
</section>
<section class="section-games">
  <div class="row">
    <div class="label-section">Voucher Game</div>
  </div>
  <div class="row gy-4 gy-md-5 py-3 py-lg-4">
    @include('frontend.home._card-games')
  </div>
  <div class="row justify-content-center mt-3 py-5 px-3">
    <a href="#" class="button__primary">
      Game Lainnya
    </a>
  </div>
</section>
<section class="section-promo-info">
  <div class="row">
    <div class="label-section">Berita Info</div>
  </div>
  <div class="row row-cols-1 gy-5 py-3 py-lg-4">
    @include('frontend.home._info-video')
    @include('frontend.home._info-news')
  </div>
  <div class="row justify-content-center mt-3 py-5 px-3">
    <a href="#" class="button__primary">
      Lihat Semua
    </a>
  </div>
</section>
@endsection