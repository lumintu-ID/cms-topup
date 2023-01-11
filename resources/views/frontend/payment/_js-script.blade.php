<script src="{{ asset('assets/website/js/jquery-3.5.1.slim.min.js') }}"></script>
<script>
  'use strict';

  $(document).ready(function(){
    const baseUrl = window.location.origin;
    const dataGame = JSON.parse(document.getElementsByClassName('games-info__body')[0].dataset.game);
    const textInfo = JSON.parse(document.getElementsByClassName('player-input')[0].dataset.infotext);
    let player;

    $(".input-feedback.input-id-player").text(textInfo.infoTextInput.idPlayer);
    $(".input-feedback.input-country").text(textInfo.infoTextInput.country);
    $(".modal-body #nameGame span").text(dataGame.title);
    $(".modal-body #nameGame :input").val(dataGame.id);
    $("#formCheckout").hide();

    const changeModalTitle = (title) => {
      $("#modalPaymentLabel").text();
      $("#modalPaymentLabel").text(title);
      return;
    }

    const showHideElement = ({ showElement = null, hideElement= null} ) => {
      $(showElement).show();
      $(hideElement).hide();
      return;
    }

    showHideElement({ showElement:'#formCheckout' })
   
    $("#btnConfirm").prop("disabled", false);
    $("#btnConfirm").click(function() {
      changeModalTitle(textInfo.titleModal.alertInfo);

      if($("#idPlayer").val() == '' ) {
        showHideElement({ showElement:'#infoCaution', hideElement: '#formCheckout' });
        $(".info-caution__empty-country, .info-caution__empty-payment, .info-caution__empty-item").attr('hidden', true);
        $(".info-caution__empty-player")
        .removeAttr('hidden')
        .text(textInfo.alert.idPlayer);
        return;
      }

      if($(".input-form__country .form-select").val() == '' || !$(".input-form__country .form-select").val()) {
        showHideElement({ showElement:'#infoCaution', hideElement: '#formCheckout' });
        $(".info-caution__empty-player, .info-caution__empty-payment, .info-caution__empty-item").attr('hidden', true);
        $(".info-caution__empty-country")
        .removeAttr('hidden')
        .text(textInfo.alert.country);
        return;
      }

      if($(".modal-body #payment input[name=payment]").val() == '' || $(".payment-list").children().length <= 0) {
        showHideElement({ showElement:'#infoCaution', hideElement: '#formCheckout' });
        $(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-item").attr('hidden', true);
        $(".info-caution__empty-payment")
        .removeAttr('hidden')
        .text(textInfo.alert.payment);
        return;
      }

      if($(".modal-body #price input[name=price]").val() == '') {
        showHideElement({ showElement:'#infoCaution', hideElement: '#formCheckout' });
        $(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-payment").attr('hidden', true);
        $(".info-caution__empty-item")
        .removeAttr('hidden')
        .text(textInfo.alert.item);
        return;
      }

      changeModalTitle(textInfo.titleModal.purchase);
      showHideElement({ showElement:'#formCheckout', hideElement: '#infoCaution' });
      return;
     
    });

    $(".total-payment__nominal").text(0);
    $("#idGameInpt").val(dataGame.id);
    $("#btnClearId").hide();
    $("#btnCheckId").click(async function(event) {
      if(!$("#idPlayer").val()) {
        $(".input-feedback.input-id-player").addClass('invalid');
        $(".input-feedback.input-id-player").text('Id Player is required');
        return;
      }
      
      await fetch(`${baseUrl}/api/v1/player`)
      .then((response) => {
        if(response.status === 404) {
          $(".input-feedback.input-id-player").removeClass('valid invalid');
          $(".input-feedback.input-id-player").addClass('invalid');
          $(".input-feedback.input-id-player").text('ID player not avaliable');
          $("#formCheckout").children('div').last().remove();
          $("#formCheckout").append('<div class="info-user">Data user tidak tersedia, silahkan coba kembali</div>');
          return;
        }
        return response.json();
      })
      .then((data) => {
        $(this).hide();
        player = data.data;
        $(".input-feedback.input-id-player").removeClass('valid invalid');
        $(".input-feedback.input-id-player").addClass('valid');
        $(".input-feedback.input-id-player").text(`Player name: ${player.username}`);
        $("#btnClearId").show();
        $(".modal-body #playerId :input").val($("#idPlayer").val());
        $(".modal-body #playerId span").text($("#idPlayer").val());
        $(".modal-body #playerName :input").val(player.username);
        $(".modal-body #playerName span").text(player.username);
        $(".modal-body #emailInpt :input").val(player.email);
        $(".modal-body #emailInpt span").text(player.email);
        $("#idPlayer").prop('disabled', true);
      });
    });

    $("#btnClearId").click(function() {
      $(this).hide();
      $("#btnCheckId").show();
      $("#idPlayer").prop('disabled', false);
      $("#idPlayer").val('');
      $(".input-feedback.input-id-player").removeClass('valid invalid');
      $(".input-feedback.input-id-player").text(textInfo.infoTextInput.idPlayer);
      $("#formCheckout").children('.info-user').remove();
      clearPlayer();
    });

    $(".input-form__country .form-select").change(async function() {
      clearPayment();
      $(".payment-list").removeClass("justify-content-center");
      if(this.value) {
        await fetch(`${baseUrl}/api/v1/payment?country=${this.value}&game_id=${dataGame.id}`)
        .then((response) => {
          if(response.status === 404) {
            $(".payment-list").empty();
            $(".payment-list").append(textInfo.noPayment);
            return;
          }
          return response.json();
        })
        .then((data) => {
          const dataPayment = data.data;
          $(".payment-list").empty();
          $(".price-list").empty();
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
            console.log(priceList);

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
              $("#formCheckout").show();
              $("#infoCaution").hide();
            });
          }); 
        })
        .catch((error) => {
          $(".payment-list").empty();
          $(".payment-list").addClass("justify-content-center");
          $(".payment-list").append(textInfo.noPayment);
          $(".price-list").empty();
        });
      }

      return;
    });
  });

  const clearPayment = () => {
    $(".modal-body #payment span").text('');
    $(".modal-body #payment input[name=payment]").val('');
    $(".modal-body #payment input[name=payment_id]").val('');
    $(".modal-body #amount span").text('');
    $(".modal-body #amount :input").val('');
    $(".modal-body #price span").text('');
    $(".modal-body #price :input").val('');
    $(".total-payment__nominal").text('0');
    return;
  }

  const clearPlayer = () => {
    $(".modal-body #playerId :input").val($("#idPlayer").val());
    $(".modal-body #playerId span").text($("#idPlayer").val());
    $(".modal-body #playerName :input").val('');
    $(".modal-body #playerName span").text('');
    return;
  }
 
</script>