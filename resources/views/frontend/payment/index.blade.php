@extends('frontend.layouts.app')
@section('content')
<section class="games-info container-fluid container-lg py-3">
  <div class="row row-cols-1 row-cols-sm-2 gy-1 pb-sm-1">
    <div class="col-12 col-md-8 p-0">
      <div class="row align-items-top align-items-sm-center align-items-md-start">
        <div class="col-4 col-sm-5 col-md-4 col-xl-3">
          <div class="games-info__image">
            <img src="{{ asset('cover/'.$dataGame->cover) }}" class="img-fluid" alt="icon-game">
          </div>
        </div>
        <div class="col-8 col-sm-7 col-md-8 ps-0 pt-2 pt-md-3">
          <div class="games-info__body" data-id="{{ $dataGame->id }}">
            <h2 class="games-info__title">{{ $dataGame->game_title }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4 d-sm-flex align-items-sm-end align-items-md-center justify-content-md-end p-0">
      <div class="games-info__button">
        <div class="row row-cols-2 row-cols-sm-1 gy-2 py-2">
          <div class="d-flex justify-content-end">
           <a href="#" class="button__primary">
              Portal Info
            </a>
          </div>
          <div class="col d-flex justify-content-end">
            <a href="#" class="button__primary">
              GAMES
            </a>
          </div>
        </div>
      </div>
    </div>     
  </div>
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
  <div class="row row-cols-1 gy-3 justify-content-center">
    <div class="col-12 col-md-8">
      <div class="total-payment__header col text-center">Total Nominal Top Up Anda</div>
    </div>
    <div class="col-12 col-md-8 col-lg-6">
      <div class="total-payment__body col text-center">
        <div class="total-payment__nominal col py-lg-3"></div>
      </div>
    </div>
    <div class="col-12 col-md-8">
      <div class="total-payment__footer col d-flex justify-content-center">
        <button class="button__primary" data-bs-toggle="modal" data-bs-target="#detailPaymentModal">konfirmasi</button>
      </div>
    </div>
  </div>
</section>
{{-- </form> --}}
<div class="back-to-home container-fluid container-lg">
  <div class="col d-flex justify-content-center p-5">
    <a href="/index.html" class="button__secondary">kembali ke beranda</a>
  </div>
</div>
<div class="back-to-home container-fluid container-lg">
  <div class="col d-flex justify-content-center p-5">
    <button class="button__secondary">Change action</button>
  </div>
</div>

<div class="modal fade" id="detailPaymentModal" tabindex="-1" aria-labelledby="detailPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Pembelian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        *Pastikan username dan ID sudah benar.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary">Lanjutkan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js-utilities')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  @include('frontend.payment._js-script')
@endsection

