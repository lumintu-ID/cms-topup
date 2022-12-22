@extends('frontend.layouts.app')
@section('content')
  <section class="container-fluid container-lg py-3">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-4 p-0">
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
                  <div class="col-6"> PPN : </div>
                  <div class="col-6 text-end"> </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Total Payment : </div>
                  <div class="col-6 text-end"> {{ $data['invoice']['total_price'] }} </div>
                </div>
                <div id="elementAttribute" data-element-input="{{ $data['attribute'] }}"></div>
              </div>
              <hr>
              <div class="box-invoice__footer row d-flex justify-content-center">
                <div class="col-8 col-md-6 p-3 d-flex justify-content-center">
                  <button type="submit" class="button__primary">Pay Now</button>
                </div>
              </div>
            </form>
          @else
            data tidak ada
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection


@section('js-utilities')
  <script>
    $(document).ready(function(){
      const payment = $("#elementAttribute").data("element-input");
      for (const key in payment) {
        if (Object.hasOwnProperty.call(payment, key)) {
          const element = payment[key];
          if(Object.keys(element) == 'methodAction') {
            $("#formInvoice").attr({
              'method': element[Object.keys(element)],
              'action': payment[0].urlAction,
            });
          }
          if(Object.keys(element) != 'methodAction' && Object.keys(element) != 'urlAction') {
            createElementInput({ 
              name: String(Object.keys(element)),
              value: element[Object.keys(element)]
            });
          }
        }
      }

    });
    const createElementInput = ({ name, value }) => {
      const elmentInput = document.createElement("input");
      elmentInput.setAttribute("name", name);
      elmentInput.hidden = true;
      elmentInput.value = value || `Value ${name} not avaliable`;
      $("#formInvoice").append(elmentInput);
      return;
    }
  </script>
@endsection