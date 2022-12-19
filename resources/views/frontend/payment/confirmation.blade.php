@extends('frontend.layouts.app')
@section('content')
  <section class="container-fluid container-lg py-3">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-4 p-0">
        <div class="box-invoice">
          {{-- @isset($invoice)
              
          @endisset --}}

          {{-- {{ dd($invoice) }} --}}

          <form action="{{ $data['payment']['url'] }}" method="" id="formInvoice">
            <div class="box-invoice__body">
              @foreach ($invoice as $item)
              @foreach ($item as $key => $value)
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Game : </div>
                  <div class="col-6 text-end">{{ $value }}</div>
                </div>
              
              
                @endforeach
              @endforeach
              
              {{-- <div id="elementAttribute" data-element-input="{{ $data['payment'] }}"></div> --}}
            </div>
            <hr>
            <div class="box-invoice__footer row d-flex justify-content-center">
              <div class="col-8 col-md-6 p-3 d-flex justify-content-center">
                <button type="submit" class="button__primary">Pay Now</button>
              </div>
            </div>
          </form>

          @if ($invoice)

            @foreach ($invoice as $item)
              @foreach ($item as $key => $value)
            
             
              @endforeach

          
            @endforeach
              
          {{-- @foreach ($invoice as $data)
            <div class="box-invoice__header d-flex justify-content-center">
              <div class="col-lg-12 text-center">
                  <h2>Invoice</h2>
                  <div class="box-invoice-header__label">
                    {{ $data['invoice']['invoice'] }}
                  </div>
              </div>
            </div>
            <form action="{{ $data['payment']['url'] }}" method="" id="formInvoice">
              <div class="box-invoice__body">
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Game : </div>
                  <div class="col-6 text-end">{{ $data['game']['game_title'] }}</div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2 ">
                  <div class="col-6"> User ID : </div>
                  <div class="col-6 text-end"> {{ $data['invoice']['id_player'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Amount : </div>
                  <div class="col-6 text-end"> {{ $data['price']['amount'] }} {{ $data['price']['name'] }} </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 py-2">
                  <div class="col-6"> Price : </div>
                  <div class="col-6 text-end"> {{ $data['price']['price'] }} </div>
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
                <div id="elementAttribute" data-element-input="{{ $data['payment'] }}"></div>
              </div>
              <hr>
              <div class="box-invoice__footer row d-flex justify-content-center">
                <div class="col-8 col-md-6 p-3 d-flex justify-content-center">
                  <button type="submit" class="button__primary">Pay Now</button>
                </div>
              </div>
            </form>
          @endforeach --}}
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
      console.log(payment);
      
      for (const key in payment.elmentAttribute) {
        if (Object.hasOwnProperty.call(payment.elmentAttribute, key)) {
          const element = payment.elmentAttribute[key];
          if(Object.keys(element) == 'methodAction') {
            $("#formInvoice").attr('method', element[Object.keys(element)]);
          }
          if(Object.keys(element) != 'methodAction') {
            createElementInput({ 
              // id: String(Object.keys(element)),
              // name: element[Object.keys(element)],
              name: String(Object.keys(element)),
              value: element[Object.keys(element)]
            });
          }
        }
      }
    });

    
    // const createElementInput = ({ id, name, value }) => {
    //   if ($("#formCheckout").find("#" + id).length <= 0) {
    //     const elmentInput = document.createElement("input");
    //     elmentInput.setAttribute("id", id);
    //     elmentInput.setAttribute("name", name);
    //     elmentInput.setAttribute("placeholder", name);
    //     elmentInput.value = value || `Value ${name} not avaliable`;
    //     $("#formInvoice").append(elmentInput);
    //     return;
    //   } else {
    //     $("#" + id).val(value);
    //     console.log('element input sudah ada');
    //     return;
    //   }

    //   return;
    // }
    
      const createElementInput = ({ name, value }) => {
        const elmentInput = document.createElement("input");
        elmentInput.setAttribute("name", name);
        elmentInput.setAttribute("placeholder", value);
        // elmentInput.hidden = true;
        elmentInput.value = value || `Value ${name} not avaliable`;
        $("#formInvoice").append(elmentInput);
        return;
      }
  </script>
@endsection