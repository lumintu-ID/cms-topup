@extends('frontend.layouts.app')
@section('content')
  <section class="container-fluid container-lg py-3">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-6 col-lg-5 col-xl-4 p-0">
        <div class="box-invoice">
          @if ($data)
            <div class="box-invoice__header d-flex justify-content-center">
              <div class="col-lg-12 text-center">
                <h2>Invoice</h2>
                <div class="box-invoice-header__label">
                  {{ $data['invoice']['invoice'] }}
                </div>
              </div>
            </div>
            <div class="box-invoice__body">
              <div class="row row-cols-1 row-cols-sm-2 py-2">
                <div class="col-6"> Date Time : </div>
                <div class="col-6 text-end"> {{ $data['invoice']['date'] }} </div>
              </div>
              <div class="row row-cols-1 row-cols-sm-2 py-2">
                <div class="col-6"> Game : </div>
                <div class="col-6 text-end"> {{ $data['game']['game_title'] }} </div>
              </div>
              <div class="row row-cols-1 row-cols-sm-2 py-2 ">
                <div class="col-6"> User ID : </div>
                <div class="col-6 text-end"> {{ $data['invoice']['id_player'] }} </div>
              </div>
              <div class="row row-cols-1 row-cols-sm-2 py-2">
                <div class="col-6"> Amount : </div>
                <div class="col-6 text-end"> 
                  {{ $data['payment']['amount'] }} {{ $data['payment']['name'] }}
                </div>
              </div>
              <div class="row row-cols-1 row-cols-sm-2 py-2">
                <div class="col-6"> Price : </div>
                <div class="col-6 text-end"> {{ $data['payment']['price'] }} </div>
              </div>
              <div class="row row-cols-1 row-cols-sm-2 py-2">
                <div class="col-6"> Method Payment : </div>
                <div class="col-6 text-end"> {{ $data['payment']['name_channel'] }} </div>
              </div>
              <div class="row row-cols-1 row-cols-sm-2 py-2">
                <div class="col-6"> Tax/PPN : </div>
                <div class="col-6 text-end"> {{ $data['payment']['ppn'] }} </div>
              </div>
              <div class="row row-cols-1 row-cols-sm-2 py-2">
                <div class="col-6"> Total Payment : </div>
                <div class="col-6 text-end"> {{ $data['invoice']['total_price'] }} </div>
              </div>
              {{-- <div id="elementAttribute" data-element-input="{{ $data['attribute'] }}"></div> --}}
            </div>
            <hr>
            <div class="box-invoice__footer row d-flex justify-content-center">
              <div class="col-8 col-md-6 p-3 d-flex justify-content-center">
                Pembayaran berhasil
              </div>
            </div>
          @else
            data tidak ada
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
