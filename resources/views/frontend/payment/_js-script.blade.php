<script>
  'use strict';

  $(document).ready(function(){
    const baseUrl = window.location.origin;
    const dataGame = JSON.parse(document.getElementsByClassName('games-info__body')[0].dataset.game);
    let player;
    // console.log(player);
    $(".modal-body #nameGame span").text(dataGame.title);
    $(".modal-body #nameGame :input").val(dataGame.id);
    $("#formCheckout").hide();

    $("#infoCaution button").click(function() {
      console.log('close modal');
      // console.log($(".info-caution__empty-all"));
      // $(".info-caution__empty-all").attr('hidden');
      // $(".info-caution__empty-all").toggle();
    });
    
    $("#btnConfirm").prop("disabled", false);
    $("#btnConfirm").click(function() {
      console.log('konfirmasi');
      // console.log($(".modal-body #playerName :input").val());
      // if(!isNaN($(".modal-body #playerName :input").val())) {
      let idPlayer = false;
      let price = true;

      if(idPlayer) {
        console.log('id player hasus diisi');
        console.log($(".info-caution__empty-all"));
        $(".info-caution__empty-all").removeAttr('hidden');
        return;
      }
      // if(isNaN($(".modal-body #playerName :input").val())) {
      //   // console.log('id player hasus diisi');
      //   // console.log($(".info-caution__empty-all"));
      //   // $(".info-caution__empty-all").removeAttr('hidden');
      //   console.log('ada')
      //   return;
      // }
      
    });
    $("#btnConfirm2").click(function() {
      console.log('giatiuadhg');
    });
    
    
    $(".total-payment__nominal").text(0);
    $("#idPlayer").val(Math.random().toString(8).slice(2));
    $("#idGameInpt").val(dataGame.id);
    $("#btnClearId").hide();
    $("#btnCheckId").click(async function() {
      await fetch(`${baseUrl}/api/v1/player`)
      .then((response) => {
        if(response.status === 404) {
          $("#formCheckout").children('div').last().remove();
          $("#formCheckout").append('<div class="info-user">Data user tidak tersedia, silahkan coba kembali</div>');
          return;
        }
        return response.json();
      })
      .then((data) => {
        player = data.data;
        console.log(player);
        $(this).hide();
        $("#btnClearId").show();
        $(".modal-body #playerId :input").val($("#idPlayer").val());
        $(".modal-body #playerId span").text($("#idPlayer").val());
        $(".modal-body #playerName :input").val(player.username);
        $(".modal-body #playerName span").text(player.username);
        $(".modal-body #emailInpt :input").val(player.email);
        $(".modal-body #emailInpt span").text(player.email);
      });
    });

    $("#btnClearId").click(function() {
      $(this).hide();
      $("#btnCheckId").show();
      $("#idPlayer").val(Math.random().toString(8).slice(2));
      $("#formCheckout").children('.info-user').remove();
      $(".modal-body #playerName :input").val('');
    });

    $(".input-form__country .form-select").change(async function() {
      const country = this.value;
      if(country) {
        await fetch(`${baseUrl}/api/v1/payment?country=${country}&game_id=${dataGame.id}`)
        .then((response) => {
          if(response.status === 404) {
            $(".payment-list").empty();
            $(".payment-list").append('Data payment tidak tersedia');
            return;
          }
          return response.json();
        })
        .then((data) => {
          const dataPayment = data.data;
          console.log(dataPayment);
          $(".payment-list").empty();
          dataPayment.map((data) => {
            $(".payment-list").append(`
              <div class="col">
                <div class="payment-list__items" data-payment="${data.payment.payment_id}">
                  <input type="radio" id="${data.payment.payment_id}" name="payment-id" value="${data.payment.payment_id}">
                  <img src="${data.payment.logo_channel}" title="${data.payment.name_channel}" alt="${data.payment.name_channel}" class="ps-2">
                </div>
              </div>
            `);
          });
          $("#formCheckout").hide();
          $("#infoCaution").show();
          $(".payment-list__items").click(function() {
            $(this).children().prop("checked", true);
            const priceList = dataPayment.find(({payment}) => payment.payment_id == this.dataset.payment );
            // console.log(priceList);
            $(".price-list").empty();
            $(".modal-body #payment span").text(priceList.payment.name_channel);
            $(".modal-body #payment input[name=payment]").val(priceList.payment.name_channel);
            $(".modal-body #payment input[name=payment_id]").val(priceList.payment.payment_id);
            $("#formCheckout").hide();
            $("#infoCaution").show();

            priceList.price.map((data) => {
              $(".price-list").append(`
                <div class="col">
                  <div class="amount-price__wrap d-flex justify-content-between p-2" data-priceid="${data.price_id}">
                    <div class="amount-price__name-item">
                      <input type="radio" id="${data.price_id}" name="price-id" value="${data.price_id}">
                      ${data.amount} ${data.name}
                    </div>
                    <div class="amount-price__price" id="price">${data.price}</div>
                  </div>
                </div>`
              );
            });
            $(".amount-price__wrap").click(function() {
              $(this).children(".amount-price__name-item").children().prop("checked", true);
              const price = parseInt($(this).children(".amount-price__price").text());
              $(".total-payment__nominal").text(price);
              $(".modal-body #price :input").val(price);
              $(".modal-body #price span").text(price);
              $(".modal-body #amount input[name=amount]").val($(this).children(".amount-price__name-item").text());
              $(".modal-body #amount span").text($(this).children(".amount-price__name-item").text());
              $(".modal-body #priceId").val($(this).children(".amount-price__name-item").children('input').val());
              // $(".modal-body #totalPayment span").text(price);
              // $(".modal-body #totalPayment :input").val(price);
              $("#formCheckout").show();
              $("#infoCaution").hide();
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

    
  });
 
</script>