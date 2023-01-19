@extends('frontend.layouts.app')
@section('content')
  <section class="container-fluid container-lg py-3">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-lg-6 col-xl-4 p-0">
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
          // console.log(payment);
          const { urlAction, dataParse } = payment;
          // console.log(dataParse);
          event.preventDefault();

          postData(urlAction, dataParse);

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
        

          if(payment.hasOwnProperty('dataRedirectTo')) {
            const { dataRedirectTo } = payment;
            const value = JSON.parse('{"trans_id":"6pjvul9rqp","merchant_code":"FmSample","order_id":"INV-IAZtuZZ3Shx2","no_reference":"INV-IAZtuZZ3Shx2","amount":"5000","frontend_url":"https:\/\/playpay.flashmobile.co.id","signature":"3970794ebd01d8a9e0ba84627ae784676a24fe13"}');

            // const value = JSON.parse('{"status": 1,"message": "Success","url": "https://dev.unipin.com/unibox/d/DLr91674028402V9cxDjMQtp5i?lg=id","signature": "aa0e7f04194b111fe692e48cd01575f3d040169c655c165aeee13a6f9c305bda"}');

         
            createRedirectForm({ dataElement: dataRedirectTo, value });
          }
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

          // // await fetch(payment.urlAction, requestOptions)
          // // .then(response => response.text())
          // // .then(result => console.log(result))
          // // .catch((error) => {
          // //   console.error('Error:', error);
          // // });

          // const $url = 'https://jsonplaceholder.typicode.com/todos/1'
          // await fetch($url)
          // .then(response => response.text())
          // .then(result => console.log(result))
          // .catch((error) => {
          //   console.error('Error:', error);
          // });

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
      const { idForm, methodAction } = dataElement;
      if(!dataElement) return console.log('no data redirect');
      if(document.getElementById(idForm)) return console.log('form was avaliable');

      let redirectForm = document.createElement("form");
      redirectForm.setAttribute('id', idForm);
      redirectForm.setAttribute('method', methodAction);
      redirectForm.setAttribute('action', value.url);
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
      document.forms[idForm].submit();
    }

        // Example POST method implementation:
    async function postData(url = '', data = {}) {

      try {
        const response = await fetch(url, { 
          method: 'POST', // *GET, POST, PUT, DELETE, etc.
          mode: 'cors', // no-cors, *cors, same-origin
          cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
          credentials: 'include', // *include, same-origin, omit
          headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
          },
          redirect: 'follow', // manual, *follow, error
          referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
          body: JSON.stringify(data) // body data type must match "Content-Type" header
        });
        // .then(response =>  {
        //   console.log(response);
        // });
        
        // const l = 'https://jsonplaceholder.typicode.com/todos/1';
        
        // const response = await fetch(l)
        // .then(response => console.log(response.headers))
        console.log(response);
        
        return response.json(); // parses JSON response into native JavaScript objects
        
      } catch (error) {
        console.log('system error, please try again');
      }
      // Default options are marked with *
    }

   
  </script>
@endsection