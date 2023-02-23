@extends('frontend.layouts.app')
@section('content')
  <section class="container-fluid container-lg py-3">
    <div class="row justify-content-center pt-3">
      <div class="col-12 col-md-10">
        <div class="box-invoice-va">

          @if (isset($data) && $data['attribute']['status_desc'] !== null)
          
            <div class="box-invoice-va__header text-center p-4">
              Detail Purchases
            </div>
            <div class="box-invoice-va__body">
              <div class="row justify-content-between  ">
                <div class="col-12 col-sm-4 p-2 ps-1 ">
                  <div class="title__invoice">No Invoice</div>
                  {{ $data['invoice']['invoice'] }}
                </div>
                <div class="col-12 col-sm-3 col-xl-2 p-2 ps-1">
                  <div class="title__invoice-date">Date Purchased 
                    <br> 
                    <span>
                      {{ date('d-m-Y H:s:i', strtotime($data['invoice']['date'])) }}</div>
                    </span>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="wrap_info row row-cols-4 justify-content-evenly bg-white py-2 px-md-2">
                    <div class="col-12 col-md-3 py-2 py-md-4">
                      <div class="title">Games</div>
                      {{ $data['game']['game_title'] }}
                    </div>
                    <div class="col-12 col-md-3 py-2 py-md-4">
                      <div class="title">Player ID</div>
                      {{ $data['invoice']['id_player'] }}
                    </div>
                    <div class="col-12 col-md-3 py-2 py-md-4">
                      <div class="title">Item</div>
                      {{ $data['payment']['amount'].' '.$data['payment']['name'] }}
                    </div>
                    <div class="col-12 col-md-3 py-2 py-md-4">
                      <div class="title">Total</div>
                      {{ $data['payment']['price'] }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="wrap_info row row-cols-4 justify-content-around bg-white pt-2 px-md-2">
                    <div class="col-12 col-md-4 col-lg-3 py-2 py-md-4">
                      <div class="title">No Virtual Account</div>
                      {{ $data['attribute']['va_number'] }}
                    </div>
                    <div class="col-12 col-md-3 py-2 py-md-4">
                      <div class="title">Payment</div>
                      {{ $data['payment']['name_channel'] }}
                    </div>
                    <div class="status-payment col-12 col-md-2 col-lg-3 py-2 py-md-4">
                      <div class="title ">Status</div>
                      <div class="text-uppercase">
                        <div class="status__pending">
                          {{ $data['attribute']['status_desc'] }}
                        </div>
                      </div>
                    </div>
                    <div class="expire-date col-12 col-md-3 py-2 py-md-4">
                      <div class="title">Expire Date</div>
                      {{-- <div class="status__pending"> --}}
                        {{ date('d-m-Y, G:i:s', strtotime($data['attribute']['expired_time'])) }}
                      {{-- </div> --}}

                      {{-- @php
                          
                          $mytime = Carbon\Carbon::now();
                          echo $mytime->toDateTimeString();
                      @endphp --}}
                      {{-- <p class="status__left-time">2 hours 45 minute left (dummy info).</p> --}}
                      <div class="status__left-time" id="leftTime">
                        {{ $data['attribute']['leftTime'] }}
                        {{-- 2 hours 45 minute left (dummy info). --}}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="mx-3">
            <div class="box-invoice-va__footer">
              *Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odio, saepe alias laborum quisquam, sunt magnam ducimus eum, esse quas pariatur temporibus similique sed cupiditate laboriosam delectus suscipit nobis quis adipisci.
            </div>
          </div>
         
        @else
          data tidak ada
        @endif
      </div>
    </div>
    {{-- <div class="row justify-content-center">
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
    </div> --}}
  </section>
@endsection

@section('js-utilities')
  <script src="{{ asset('assets/website/js/jquery-3.5.1.slim.min.js') }}"></script>
  @vite(['resources/js/confirmation-va.js'])
@endsection