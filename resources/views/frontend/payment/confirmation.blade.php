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
          console.log(payment);
          // const { urlAction, dataParse } = payment;
          // console.log(dataParse);
          event.preventDefault();

          // postData(payment);

          // const $url = 'https://jsonplaceholder.typicode.com/todos/1'
          // await fetch(urlAction)
          // .then(response => console.log(response.headers))
          // .then(result => {
          //   // const { completed } = JSON.parse(result);
          //   console.log(result);
            
          //   // if(!completed){
          //   //   result.idForm = 'formRedirectMp';
          //   //   console.log(result);
          //   //   createRedirectForm({ dataElement: dataRedirectTo, value });
          //   // }
            
          // })
          // .catch((error) => {
          //   console.error('Error:', error);
          // });

          // createElementInput()
        

          // // let headers = new Headers();
          // // headers.append('Content-Type', 'application/json');
          // // headers.append('Accept', 'application/json');
          // // headers.append('Access-Control-Allow-Origin', 'http://localhost:8000');
          // // headers.append('Access-Control-Allow-Credentials', 'true');
          // // headers.append('GET', 'POST', 'OPTIONS');

          // if(payment.hasOwnProperty('dataRedirectTo')) {
            const { dataRedirectTo, dataParse } = payment;
            console.log(dataParse);
            // const value = JSON.parse('{"trans_id":"hfvzs7anxxni","merchant_code":"FmSample","order_id":"INV-Fhr86L5w8CpR","no_reference":"INV-Fhr86L5w8CpR","amount":"5000","frontend_url":"https:\/\/playpay.flashmobile.co.id","signature":"9de5951c68692643acb7c465d91a93abeaca91d5"}');

            // const value = JSON.parse('{"status": 1, "message": "Success", "url": "https://dev.unipin.com/unibox/d/LDPW1674201556tQK3nQ0N33F1?lg=id", "signature": "9eb208297db7849f7f0f3698c0278fe1f83387a6bd53f1186b7a03266edec27e"}');

            // const value = JSON.parse('{"paymentId": "MPO2016930","referenceId": "INV-xfuUDRjaD7jP","paymentUrl": "https://global.gold-sandbox.razer.com/PaymentWall/Checkout/index?token=qipDtsNLDSKXMmPfKpJrjzWvXmAnRCHsQKwLYKtevTtJI4I5sLUeO9K%2f4zzk%2fgB0cVHepJ2dG%2f9ZX%2bEJOou9ou4nGNUkR3cXXShUHur6GdDOs8xAkeg4miQ5b7IuxwYekYYuzy7x55WhzOGv%2fg%2fcFVlgZ2YT4psWTCmXOa0SQg%2fWhJrHnO1Vwnp2TLRiKDa5anJmJ164eIjR%2b82Ovu5wIwlRBitV2cScnSQGJo6gNNroY7%2bt%2fbTdkZxrqq%2f3EcVOxBO%2fuivc52Q%3d","amount": 50000,"currencyCode": "IDR","hashType": "hmac-sha256","version": "v1","signature":"8f966082a8865c27382b1090e74790ce06546cc27fc5341d47e2f81b470b961a","applicationCode": "WG12Nu61SaXhQieGcmW7yYWhKp9xBwvn"}');

         
            // createRedirectForm({ dataElement: dataRedirectTo });
          // }
        // console.log(dataRedirectTo);
          // let headers = {'Content-Type':'application/json',
          //           'Access-Control-Allow-Origin':'*',
          //           'Access-Control-Allow-Methods':'POST,PATCH,OPTIONS'}
          // console.log(headers);
          // console.log(payment.dataparse);
          // console.log(payment.urlAction);
          // console.log(payment.redirectToPayment);
          // console.log(JSON.stringify( payment.dataparse));
          // // await fetch(payment.urlAction, {
          // //   method: payment.methodAction,
          // //   headers: headers,
          // //   body: JSON.stringify( payment.dataparse),
          // // })
          // // .then((response) => {
          // //   console.log(response);
          // // });
          // pageRedirect();
          // let myHeaders = new Headers();
          // myHeaders.append("Content-Type", "application/json");
          // myHeaders.append('Access-Control-Allow-Origin', '*');
          // myHeaders.append('Access-Control-Allow-Methods', 'POST, PATCH, OPTIONS');
          // myHeaders.append('Access-Control-Allow-Credentials', true);
          // myHeaders.append('Access-Control-Allow-Headers', 'X-Api-Key, X-Requested-With, Content-Type, Accept, Authorization');
          // let requestOptions = {
          //   method: dataParse.methodAction,
          //   headers: myHeaders,
          //   mode: 'cors',
          //   body: JSON.stringify( dataParse),
          //   redirect: 'follow'
          // };

          // await fetch(payment.urlAction, requestOptions)
          // .then(response => response.text())
          // .then(result => console.log(result))
          // .catch((error) => {
          //   console.error('Error:', error);
          // });

          // const $url = 'https://jsonplaceholder.typicode.com/todos/1'
          // await fetch($url)
          // .then(response => response.text())
          // .then(result => {
            
          //   createRedirectForm(dataParse);
          //   // console.log(result);

          // })
          // .catch((error) => {
          //   console.error('Error:', error);
          // });
        });
      }
    });

    const createElementInput = ({ name, value, idForm }) => {
      const elmentInput = document.createElement("input");
      elmentInput.setAttribute("name", name);
      elmentInput.hidden = false;
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
      inputSubmit.hidden = false;
      document.getElementById(idForm).append(inputSubmit);
      // document.forms[idForm].submit();
    }

        // Example POST method implementation:
    async function postData({ urlAction, methodAction, contentType = null, dataParse }) {
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