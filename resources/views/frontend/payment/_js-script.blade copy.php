<script>
  'use strict';
  // const merchantId = 'Esp5373790';
  let urlCheckout; 
  let dataPayment; 
  let country = null;
  let amount = 0;
  let datetrx = new Date().toISOString().slice(0, 19) + '+07';
  let nameUser;
  let emailUser;
  // let phoneUser = '08777535648447';
  let phoneUser = '081240157378';
  let channelId;
  let currency = 'IDR';
  let returnUrl = window.location.href;

  const attrPayment = {
    goc: {
      idPlayer: 'userId',
      name: 'name',
      email: 'email',
      channelName: 'channelId',
      userId: 'userId',
      sign: 'sign'
    },
    gv: {
      name: 'nameUser',
      email: 'email',
      channelName: 'product',
    }
  }
  
  $(document).ready(function(){
    const baseUrl = window.location.origin;
    const idGame = document.getElementsByClassName('games-info__body')[0].dataset.id;
    
    if(!country) $(".payment-list").append('Silahkan pilih negara');
    $(".input-form__player").append($("<input />").attr({
      id: 'idPlayer',
      type: "text",
      class: 'form-control',
      placeholder: 'ID USER',
    }), $("<button />").text("Check").addClass("input-group-text").attr({
      id: "btnCheckId",
      type: "button",
    }), $("<button />").text("Clear").addClass("input-group-text").attr({
      id: "btnClear",
      type: "button",
    }).hide());
  
    $(".total-payment__nominal").text(amount);
    
    $("#btnCheckId").click(async function() {
      $("#idPlayer").val(Math.random().toString(8).slice(2));
      await fetch(`${baseUrl}/api/v1/player`)
      .then((response) => {
        if(response.status === 404) {
          $("#formCheckout").children('div').last().remove();
          $("#formCheckout").append('<div>Data user tidak tersedia, silahkan coba kembali</div>');
          return;
        }
        return response.json();
      })
      .then((data) => {
        console.log(data.data);
        nameUser = data.data.username;
        emailUser = data.data.email;
        if($("#formCheckout").find("#userName").length <= 0) {
          createElementInput({ id: 'userName', name: attrPayment.goc.name, value: nameUser });
        } else {
          $("#userName").val(nameUser);
        };
        if($("#formCheckout").find("#emailUser").length <= 0) {
          createElementInput({ id: 'emailUser', name: 'email', value: emailUser });
        } else {
          $("#emailUser").val(emailUser);
        };
        $(this).hide();
        $("#btnClear").show();
      })
      .catch((error) => {
        
      });
    });

    $("#btnClear").click(function() {
      $(this).hide();
      $("#btnCheckId").show();
      $("#idPlayer").val("");
      $("#userName").val("");
      $("#emailUser").val("");
    });

    $(".input-form__country .form-select").change(async function(){
      country = this.value;
      if(country) {
        await fetch(`${baseUrl}/api/v1/payment?country=${country}&game_id=${idGame}`)
        .then((response) => {
          if(response.status === 404) {
            $(".payment-list").empty();
            $(".payment-list").append('Data payment tidak tersedia');
            return;
          }
          return response.json();
        })
        .then((data) => {
          dataPayment = data.data;
          $(".payment-list").empty();
          dataPayment.map((data) => {
            $(".payment-list").append(`
              <div class="col">
                <div class="payment-list__items" data-payment="${data.payment.payment_id}">
                  <input type="radio" id="${data.payment.payment_id}" name="radio-button-payment" value="${data.payment.payment_id}">
                  <img src="${data.payment.logo_channel}" title="${data.payment.name_channel}" alt="${data.payment.name_channel}">
                </div>
              </div>
            `);
          });
          $(".payment-list__items").click(function() {
            $(this).children().prop("checked", true);
            $("#idPlayer").attr("name", attrPayment.goc.idPlayer)
            const priceList = dataPayment.find(({payment}) => payment.payment_id == this.dataset.payment );
            channelId = priceList.payment.channelId;
            if($("#formCheckout").find("#channelId").length <= 0) {
              createElementInput({ id:'channelId', name: attrPayment.goc.channelName, value: channelId });
            } else {
              $("#channelId").val(channelId);
            };
            
            urlCheckout = priceList.payment.url;
            $('#formCheckout').attr('action', urlCheckout);
            
            $(".price-list").empty();
            priceList.price.map((data) => {
              $(".price-list").append(`
                <div class="col">
                  <div class="amount-price__wrap d-flex justify-content-between p-2" data-priceid="${data.price_id}">
                    <div class="amount-price__name-item">
                      <input type="radio" id="${data.price_id}" name="radio-button-price" value="${data.price_id}">
                      Stone
                    </div>
                    <div class="amount-price__price">${data.price}</div>
                  </div>
                </div>`
              );
            });
            $(".amount-price__wrap").click(function() {
              $(this).children(".amount-price__name-item").children().prop("checked", true)
              amount = parseInt($(this).children('.amount-price__price').text());
              $(".total-payment__nominal").text(amount);
              
              if($("#formCheckout").find("#amountInput").length <= 0) {
                createElementInput({ id:'amountInput', name:'amount', value: amount });
                $("#formCheckout").append(amountInput);
              } else {
                $("#amountInput").val(amount);
              };
              // $('input[name="sign"]').val(plainSign);
            });
          }); 
        })
        .catch((error) => {
          $(".payment-list").empty();
          $(".payment-list").append('Data payment tidak tersedia');
          $(".price-list").empty();
        });
      } else {
        $(".payment-list").empty();
        $(".payment-list").append('Silahkan pilih negara');
        $(".price-list").empty();
      }
    });

    
    // $('input[name="merchantId"]').val(merchantId);
    
    const trxId = $('input[name="trxId"]').val(Math.random().toString(8).slice(2));
    $('input[name="trxDateTime"]').val(datetrx);
    $('input[name="phone"]').val(phoneUser);
    // $('input[name="currency"]').val(currency);
    // $('input[name="returnUrl"]').val(returnUrl);
  
    $(".button__primary").click(async function(event) {
      event.preventDefault();
      // await fetch(`${baseUrl}/dev/payment/generate?amount=${amount}&channelId=${channelId}`)
      await fetch(`${baseUrl}/dev/payment/generategv?amount=${amount}&channelId=${channelId}`)
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        console.log(data);
        createElementInput({ id: 'merchantInput', name: 'merchantid', value: data.data.merchantId });
        createElementInput({ id: 'productInput', name: 'product', value: data.data.product });
        createElementInput({ id: 'customInput', name: 'custom', value: data.data.trxId });
        createElementInput({ id: 'signInput', name: 'signature', value: data.data.sign });
        createElementInput({ id: 'urlInput', name: 'custom_redirect', value: returnUrl });
        // createElementInput({ id: 'trxIdInput', name: 'trxId', value: data.data.trxId });
        // createElementInput({ id: 'trxDateTimeInput', name: 'trxDateTime', value: data.data.trxDateTime });
        // createElementInput({ id: 'merchantInput', name: 'merchantId', value: data.data.merchantId });
        // createElementInput({ id: 'signInput', name: 'sign', value: data.data.sign });
        // setTimeout(function() {
        //   $('#formCheckout').submit();
        // }, 1000);
        $('#formCheckout').submit();
      })
      .catch((error) => {
        console.log(error);
      });
    });
  });

  const createElementInput = ({ id, name, value }) => {
    if($("#formCheckout").find("#" + id).length <= 0) {
      const elmentInput = document.createElement("input");
      elmentInput.setAttribute("id", id);
      elmentInput.setAttribute("name", name);
      elmentInput.setAttribute("placeholder", name);
      elmentInput.value = value || null;
      $("#formCheckout").append(elmentInput);
      return;
    } else {
      $("#" + id).val(value);
      console.log('element input sudah ada');
      return;
    }

    return;
  }
 
</script>