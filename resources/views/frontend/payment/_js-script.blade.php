<script>
  'use strict';

  $(document).ready(function(){
    const baseUrl = window.location.origin;
    const idGame = document.getElementsByClassName('games-info__body')[0].dataset.id;
    let player;
    $(".total-payment__nominal").text(0);

    $("#idPlayer").val(Math.random().toString(8).slice(2));
    $("#idGameInpt").val(idGame);

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
        $(".modal-body #idPlayer").val($("#idPlayer").val());
        $(".modal-body #playerName").val(player.username);
        $(".modal-body #emailInpt").val(player.email);
      });
    });

    $("#btnClearId").click(function() {
      $(this).hide();
      $("#btnCheckId").show();
      $("#idPlayer").val(Math.random().toString(8).slice(2));
      $("#formCheckout").children('.info-user').remove();
    });

    $(".input-form__country .form-select").change(async function() {
      const country = this.value;
      console.log(country);
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
          $(".payment-list__items").click(function() {
            $(this).children().prop("checked", true);
            const priceList = dataPayment.find(({payment}) => payment.payment_id == this.dataset.payment );
            console.log(priceList);
            $(".price-list").empty();
            $(".modal-body #paymentId").val(priceList.payment.payment_id);
            $(".modal-body #payment").val(priceList.payment.name_channel);
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
              const priceId = $(this).children(".amount-price__name-item").children('input').val();
              const amount = $(this).children(".amount-price__name-item").text();
              const price = parseInt($(this).children(".amount-price__price").text());
              $(".total-payment__nominal").text(price);
              $(".modal-body #price").val(price);
              $(".modal-body #amountInpt").val(amount);
              $(".modal-body #priceId").val(priceId);
              $(".modal-body #totalPayment").val(price);
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