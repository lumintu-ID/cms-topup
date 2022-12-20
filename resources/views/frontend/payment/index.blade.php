@extends('frontend.layouts.app')
@section('content')
<section class="games-info container-fluid container-lg py-3">
  @include('frontend.payment._info-game')
</section>
<section class="section-player container-fluid container-lg">
  @include('frontend.payment._player-input')
</section>
<section class="section-payment-list container-fluid container-lg">
  <div class="payment-list row row-cols-2 row-cols-xl-4 g-2"></div>
</section>
<section class="section-amount-list container-fluid container-lg">
  <div class="price-list row row-cols-1 row-cols-md-3 row-cols-lg-4 gx-1 gx-md-3 gy-2"></div>
</section>
<section class="section-total-payment container-fluid container-lg py-lg-4">
  @include('frontend.payment._total-payment')
</section>
<div class="back-to-home container-fluid container-lg">
  <div class="col d-flex justify-content-center p-5">
    <a href="/index.html" class="button__secondary">kembali ke beranda</a>
  </div>
</div>
@include('frontend.payment._modal-confirmation')
@endsection

@section('js-utilities')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  @include('frontend.payment._js-script')
@endsection