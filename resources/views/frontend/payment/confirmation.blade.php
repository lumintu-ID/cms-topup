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
                <div id="elementAttribute" data-element-input="{{ $data['attribute'] }}"></div>
              </div>
              <hr>
              <div class="box-invoice__footer row d-flex justify-content-center">
                <div class="col-8 col-md-6 p-3 d-flex justify-content-center">
                  <button type="submit" class="button__primary" id="btnPay">Pay Now</button>
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
  <script src="{{ asset('assets/website/js/jquery-3.5.1.slim.min.js') }}"></script>
  <script>
    $(document).ready(function(){
      const payment = $("#elementAttribute").data("element-input");
      const divParseElement = document.createElement("div");
      divParseElement.style.display = "none";
      divParseElement.setAttribute('id', 'parseElement');
      document.getElementById('formInvoice').append(divParseElement);
      if(!payment.hasOwnProperty('dataParse')){
        for (const key in payment) {
          if (Object.hasOwnProperty.call(payment, key)) {
            const element = payment[key];
            if(element.methodAction) {
              $("#formInvoice").attr({ 'method': element[Object.keys(element)] });
              continue;
            }
            if(element.urlAction ) {
              $("#formInvoice").attr({ 'action': element[Object.keys(element)] });
              continue;
            }
            createElementInput({ 
              name: Object.keys(element),
              value: element[Object.keys(element)],
              idForm: divParseElement.getAttribute( 'id' )
            });
          }
        }
      }else{
        $("#btnPay").removeAttr('type');
        $("#btnPay").click(function(event) {
          event.preventDefault();
          const { urlAction, dataParse, dataRedirectTo } = payment;
          postData({urlAction, dataParse});
        });
      }
    });

    const createElementInput = ({ name, value, idForm }) => {
      const elmentInput = document.createElement("input");
      elmentInput.setAttribute("name", name);
      elmentInput.hidden = true;
      elmentInput.value = value || 'no value';
      document.getElementById(idForm || 'formInvoice').append(elmentInput);
      return;
    }

    const createRedirectForm = ({ dataElement = null, value = null }) => {
      if(!dataElement || !value) return console.error('No data can be process.');
      const { idForm, methodAction } = dataElement;
      if(document.getElementById(idForm)) return console.log('form was avaliable');

      let redirectForm = document.createElement("form");
      redirectForm.setAttribute('id', idForm);
      redirectForm.setAttribute('method', methodAction);
      redirectForm.setAttribute('action', value.paymentUrl);
      document.getElementsByClassName('box-invoice')[0].appendChild(redirectForm);
      
      for (const key in value) {
        if(key.includes("url")) { 
          redirectForm.setAttribute('action', value[key]);
        };
        createElementInput({ idForm, name: key, value: value[key] });
      }

      const inputSubmit = document.createElement("input");
      inputSubmit.setAttribute("type", "submit");
      inputSubmit.hidden = true;
      document.getElementById(idForm).append(inputSubmit);
      document.forms[idForm].submit();
    }

        // Example POST method implementation:
    async function postData({ urlAction = null, methodAction = null, contentType = null, dataParse = null }) {
      // console.log(dataParse);
      try {
        const response = await fetch(urlAction, { 
          method: methodAction, // *GET, POST, PUT, DELETE, etc.
          mode: 'cors', // no-cors, *cors, same-origin
          cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
          credentials: 'include', // *include, same-origin, omit
          headers: {
            'Content-Type': contentType || 'application/json',
          },
          redirect: 'follow', // manual, *follow, error
          referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
          body: JSON.stringify(dataParse) // body data type must match "Content-Type" header
        });
        
        console.log(response);
        
        return response.json(); // parses JSON response into native JavaScript objects
        
      } catch (error) {
        console.log('Failed sending data, please try again.');
      }
      // Default options are marked with *
    }

   
  </script>
@endsection