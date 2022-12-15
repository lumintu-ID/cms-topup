@extends('frontend.layouts.app')
@section('content')
  <section class="container-fluid container-lg py-3">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-6 p-0">
        <div class="box-invoice">
          @foreach ($data as $inv)
          <div class="box-invoice__header d-flex justify-content-center">
            <div class="col-lg-12 text-center">
              <h2>Invoice</h2>
                {{ $inv['invoice']['invoice'] }}
              </div>
            </div>
            <div class="box-invoice__body d-flex justify-content-center">
              <form action="{{ $inv['payment']['url'] }}">
                <div class="row row-cols-1 row-cols-sm-2 ">
                  <div class="col  bg-danger"> Game </div>
                  <div class="col  text-md-end bg-info">{{ $inv['invoice']['game_id'] }}</div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 ">
                  <div class="col col-md-5"> User ID: </div>
                  <div class="col col-md-6 text-md-end"> </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 ">
                  <div class="col col-md-5"> Price: </div>
                  <div class="col col-md-6 text-md-end"> {{ $inv['price']['price'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 ">
                  <div class="col col-md-5"> Method Payment: </div>
                  <div class="col col-md-6 text-md-end"> {{ $inv['payment']['name_channel'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 ">
                  <div class="col col-md-5"> PPN: </div>
                  <div class="col col-md-6 text-md-end"> </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 ">
                  <div class="col col-md-5"> Total Payment: </div>
                  <div class="col col-md-6 text-md-end">  {{ $inv['invoice']['total_price'] }} </div>
                </div>
              {{-- <input type="text" name="idGame" value="{{ $data->game_id }}" hidden> --}}
            </div>
            <hr>
            <div class="box-invoice__footer row d-flex justify-content-center">
              <div class="col-8 col-md-6 p-2 d-flex justify-content-center">
                <button type="submit" class="button__primary">Pay Now</button>
              </div>
            </div>
            @endforeach
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection