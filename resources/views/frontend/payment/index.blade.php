@extends('frontend.layouts.app')
@section('content')
<section class="section-games-info">
  <div class="row row-cols-1 row-cols-sm-2 gy-1 pb-sm-1">
    @include('frontend.payment._info-game')
  </div>
</section>
<section class="section-payment container-fluid container-lg">
  @include('frontend.payment._player-input')
  <div class="d-flex justify-content-center" id="paymentLoader">
    <div class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  {{-- <nav class="mt-4">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">E-Money</button>
      <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">E-Wallet</button>
      <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Carrier Billing</button>
      <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Internet Banking</button>
      <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Direct Bank Transfer</button>
    </div>
  </nav>
  <div class="payment-list row row-cols-2 row-cols-md-3 row-cols-xl-4 g-2" data-paymentcategory="{{ $categoryPayment }}">
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
        <div class="price-list row row-cols-1 row-cols-md-3 row-cols-lg-4 gx-1 gx-md-3 gy-2"></div>
        
      </div>
      <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ratione nemo consequatur soluta sed inventore, mollitia fugiat possimus quidem fugit rerum itaque a iure accusamus. Dolorem modi sequi dolor sint facere.</div>
      <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
      <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
    </div>
  </div> --}}
  <div class="payment-list row row-cols-2 row-cols-md-3 row-cols-xl-4 g-2" data-paymentcategory="{{ $categoryPayment }}"></div>
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
  <script src="{{ asset('assets/website/js/jquery-3.5.1.slim.min.js') }}"></script>
  @vite(['resources/js/payment.js'])
@endsection