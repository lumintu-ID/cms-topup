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
            <form id="formInvoice">
              @csrf
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
                  <div class="col-6"> VA Number : </div>
                  <div class="col-6 text-end"> {{ $data['attribute']['va_number'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Total : </div>
                  <div class="col-6 text-end"> {{ $data['attribute']['amount'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Method Payment : </div>
                  <div class="col-6 text-end"> {{ $data['payment']['name_channel'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Expired Time : </div>
                  <div class="col-6 text-end"> {{ $data['attribute']['expired_time'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Status : </div>
                  <div class="col-6 text-end"> {{ $data['attribute']['status_desc'] }} </div>
                </div>
              </div>
              <hr>
            </form>
          @else
            data tidak ada
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection

{{-- @section('js-utilities')
  <script src="{{ asset('assets/website/js/jquery-3.5.1.slim.min.js') }}"></script>
  @vite(['resources/js/confirmation.js'])
@endsection --}}