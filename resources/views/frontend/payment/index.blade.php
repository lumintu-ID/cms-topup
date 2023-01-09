@extends('frontend.layouts.app')
@section('content')
<section class="section-games-info">
  <div class="row row-cols-1 row-cols-sm-2 gy-1 pb-sm-1">
    @include('frontend.test._info-game')
  </div>
</section>
<section class="section-payment container-fluid container-lg">
  @include('frontend.payment._player-input')
  <div class="payment-list row row-cols-2 row-cols-md-3 row-cols-xl-4 g-2"></div>
  <div class="price-list row row-cols-1 row-cols-md-3 row-cols-lg-4 gx-1 gx-md-3 gy-2"></div>
</section>
<section class="section-total-payment container-fluid container-lg py-lg-4">
  @include('frontend.payment._total-payment')
</section>
<div class="back-to-home container-fluid container-lg">
  <div class="col d-flex justify-content-center p-5">
    <a href="{{ route('home') }}" class="button__secondary">Home</a>
  </div>
</div>
@include('frontend.payment._modal-confirmation')
@endsection

@section('js-utilities')
  @include('frontend.payment._js-script')
@endsection