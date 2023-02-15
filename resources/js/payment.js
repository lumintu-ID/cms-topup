'use strict';

$(document).ready(function () {
  const baseUrl = window.location.origin;
  const dataGame = JSON.parse(document.getElementsByClassName('games-info__body')[0].dataset.game);
  const categoryPayment = JSON.parse(document.getElementById('nav-tab-payment').dataset.paymentcategory);
  const textInfo = JSON.parse(document.getElementsByClassName('player-input')[0].dataset.infotext);
  let player;

  $(".input-feedback.input-id-player").text(textInfo.infoTextInput.idPlayer);
  $(".input-feedback.input-country").text(textInfo.infoTextInput.country);
  $(".modal-body #nameGame span").text(dataGame.title);
  $(".modal-body #nameGame :input").val(dataGame.id);
  $("#nav-tab-payment").hide();
  $(".info-payment").hide();
  $("#formCheckout").hide();
  initInputPhone();
  listNavTab(categoryPayment);
  showHideElement({ showElement: '#formCheckout' })

  $("#btnConfirm").prop("disabled", false);
  $("#btnConfirm").click(function () {
    changeModalTitle(textInfo.titleModal.alertInfo);

    if ($("#idPlayer").val() == '') {
      showHideElement({ showElement: '#infoCaution', hideElement: '#formCheckout' });
      $(".info-caution__empty-country, .info-caution__empty-payment, .info-caution__empty-phone, .info-caution__empty-item").attr('hidden', true);
      $(".info-caution__empty-player")
        .removeAttr('hidden')
        .text(textInfo.alert.idPlayer);
      return;
    }

    if ($(".modal-body #playerName input[name=username]").val() == '' && $("#idPlayer").val() != '') {

      showHideElement({ showElement: '#infoCaution', hideElement: '#formCheckout' });
      $(".info-caution__empty-country, .info-caution__empty-payment, .info-caution__empty-phone, .info-caution__empty-item").attr('hidden', true);
      $(".info-caution__empty-player")
        .removeAttr('hidden')
        .text(textInfo.alert.checkIdPlayer);
      return;
    }

    if ($(".input-form__country .form-select").val() == '' || !$(".input-form__country .form-select").val()) {
      showHideElement({ showElement: '#infoCaution', hideElement: '#formCheckout' });
      $(".info-caution__empty-player, .info-caution__empty-payment, .info-caution__empty-phone, .info-caution__empty-item").attr('hidden', true);
      $(".info-caution__empty-country")
        .removeAttr('hidden')
        .text(textInfo.alert.country);
      return;
    }

    if ($(".modal-body #payment input[name=payment]").val() == '' || $(".payment-list").children().length <= 0) {
      showHideElement({ showElement: '#infoCaution', hideElement: '#formCheckout' });
      $(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-item, .info-caution__empty-phone").attr('hidden', true);
      $(".info-caution__empty-payment")
        .removeAttr('hidden')
        .text(textInfo.alert.payment);
      return;
    }

    if ($(".modal-body #phone").length > 0) {
      const patternPhone = /^0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/gm;

      if ($(".input-form__phone input[name=phone]").val() == '') {
        showHideElement({ showElement: '#infoCaution', hideElement: '#formCheckout' });
        $(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-payment, .info-caution__empty-item").attr('hidden', true);
        $(".info-caution__empty-phone")
          .removeAttr('hidden')
          .text(textInfo.alert.phone);
        return;
      }

      if (!patternPhone.test($(".input-form__phone input[name=phone]").val())) {
        showHideElement({ showElement: '#infoCaution', hideElement: '#formCheckout' });
        $(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-payment, .info-caution__empty-item").attr('hidden', true);
        $(".info-caution__empty-phone")
          .removeAttr('hidden')
          .text('Phone number is invalid');
        return;
      };

      $(".modal-body #phone :input").val($(".input-form__phone input[name=phone]").val());
      $(".modal-body #phone span").text($(".input-form__phone input[name=phone]").val());
    }

    if ($(".modal-body #price input[name=price]").val() == '') {
      showHideElement({ showElement: '#infoCaution', hideElement: '#formCheckout' });
      $(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-payment, .info-caution__empty-phone").attr('hidden', true);
      $(".info-caution__empty-item")
        .removeAttr('hidden')
        .text(textInfo.alert.item);
      return;
    }

    changeModalTitle(textInfo.titleModal.purchase);
    showHideElement({ showElement: '#formCheckout', hideElement: '#infoCaution' });
    return;

  });

  $(".total-payment__nominal").text(0);
  $("#idGameInpt").val(dataGame.id);
  $("#btnClearId").hide();
  $("#btnCheckId").click(async function (event) {
    if (!$("#idPlayer").val()) {
      $(".input-feedback.input-id-player").addClass('invalid');
      $(".input-feedback.input-id-player").text(textInfo.infoTextInput.warning);
      return;
    }

    await fetch(`${baseUrl}/api/v1/player`)
      .then((response) => {
        if (response.status === 404) {
          $(".input-feedback.input-id-player").removeClass('valid invalid');
          $(".input-feedback.input-id-player").addClass('invalid');
          $(".input-feedback.input-id-player").text(textInfo.infoTextInput.playerNotFound);
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
      })
      .catch(e => {
        console.error(e);
      });
  });

  $("#btnClearId").click(function () {
    $(this).hide();
    $("#btnCheckId").show();
    $("#idPlayer").prop('disabled', false);
    $("#idPlayer").val('');
    $(".input-feedback.input-id-player").removeClass('valid invalid');
    $(".input-feedback.input-id-player").text(textInfo.infoTextInput.idPlayer);
    $("#formCheckout").children('.info-user').remove();
    clearPlayer();
  });

  $(".input-form__country .form-select").change(async function () {
    $("#paymentLoader, #paymentLoader .spinner-border").addClass('mt-5').show();
    $("#nav-tab-payment").hide();
    $(".info-payment").hide();
    $(".info-payment").empty();
    $(".payment-list").empty();
    $(".price-list").empty();
    $(".phone").empty();

    clearPayment();
    if (this.value) {
      await fetch(`${baseUrl}/api/v1/payment?country=${this.value}&game_id=${dataGame.id}`)
        .then((response) => {
          if (response.status === 404) {
            addRemoveClass({ element: ".info-payment", addClass: "justify-content-center" })
            $("#nav-tab-payment").hide();
            $(".info-payment").show();
            $(".info-payment").empty();
            $(".info-payment").append(textInfo.noPayment);
            return;
          }
          return response.json();
        })
        .then((data) => {
          $("#nav-tab-payment").show();
          $(".info-payment").hide();
          addRemoveClass({ element: ".payment-list", addClass: "justify-content-start", removeClass: "justify-content-center" })
          const dataPayment = data.data;
          $("#paymentLoader, #paymentLoader .spinner-border").removeClass('mt-5').hide();
          $(".payment-list").empty();
          $("#nav-tab .nav-link").click(function () {
            removeElement({ classElement: 'phone' })
            $(".price-list").empty();
            $(".payment-list__items").children().prop("checked", false);
            clearItems();
          });
          dataPayment.map(({ payment }) => {
            addTabContent(payment, categoryPayment);
          });
          $("#formCheckout").hide();
          $("#infoCaution").show();
          $(".payment-list__items").click(function () {
            clearItems();
            $(".modal-body #phone").remove();
            $(this).children().prop("checked", true);
            const { payment, price } = dataPayment.find(({ payment }) => payment.payment_id == this.dataset.payment);
            createPhoneInput(payment);
            $(".price-list").empty();
            $(".modal-body #payment span").text(payment.name_channel);
            $(".modal-body #payment input[name=payment]").val(payment.name_channel);
            $(".modal-body #payment input[name=payment_id]").val(payment.payment_id);
            $("#formCheckout").hide();
            $("#infoCaution").show();

            price.map((data) => {
              $(".price-list").append(`<div class="col"><div class="amount-price__wrap d-flex justify-content-between p-2" data-priceid="${data.price_id}"><div class="amount-price__name-item"><input type="radio" id="${data.price_id}" name="price-id" value="${data.price_id}"> ${data.amount} ${data.name}</div><div class="amount-price__price" id="price">${data.price}</div></div></div>`);
            });

            $(".amount-price__wrap").click(function () {
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
          $("#nav-tab-payment").hide();
          $("#paymentLoader, #paymentLoader .spinner-border").removeClass('mt-5').hide();
          $(".payment-list").empty();
          $(".payment-list").addClass("justify-content-center");
          $(".payment-list").append(textInfo.noPayment);
          $(".price-list").empty();
          $(".info-payment").show();
        });
    }
    return;
  });
});

const changeModalTitle = (title) => {
  $("#modalPaymentLabel").text();
  $("#modalPaymentLabel").text(title);
  return;
}

const showHideElement = ({ showElement = null, hideElement = null }) => {
  $(showElement).show();
  $(hideElement).hide();
  return;
}

const listNavTab = (data) => {
  data.map(({ category, id }, index) => {
    if (index == 0) {
      $("#nav-tab").append(`<button class="nav-link active" id="nav-${id}-tab" data-bs-toggle="tab" data-bs-target="#nav-${id}" type="button" role="tab" aria-controls="nav-${id}" aria-selected="true">${category}</button>`);
      $("#nav-tabContent").append(`<div class="tab-pane fade show active" id="nav-${id}" role="tabpanel" aria-labelledby="nav-${id}-tab" tabindex="0"><div class="payment-list row row-cols-2 row-cols-md-3 row-cols-xl-4 g-2"></div></div>`)
      return;
    }

    $("#nav-tab").append(`<button class="nav-link" id="nav-${id}-tab" data-bs-toggle="tab" data-bs-target="#nav-${id}" type="button" role="tab" aria-controls="nav-${id}" aria-selected="true">${category}</button>`);
    $("#nav-tabContent").append(`<div class="tab-pane fade show" id="nav-${id}" role="tabpanel" aria-labelledby="nav-${id}-tab" tabindex="0"><div class="payment-list row row-cols-2 row-cols-md-3 row-cols-xl-4 g-2"></div></div>`)
    return;
  });
}

const addTabContent = ({ category_id, payment_id, name_channel, logo_channel }) => {
  $("#nav-" + category_id + " .payment-list").append(`<div class="col"><div class="payment-list__items" data-payment="${payment_id}"><input type="radio" id="${payment_id}" name="payment-id" value="${payment_id}"><img src="${logo_channel}" title="${name_channel}" alt="${name_channel}" class="ps-2"></div></div>`);
  return;
}

const createPhoneInput = ({ phone_required }) => {
  if (phone_required) {
    $(".wrap-phone").show({
      complete: createPhoneOnModal()
    });
    return;
  }
  $(".wrap-phone").hide();
  return;
}

const createPhoneOnModal = () => {
  $(".modal-body .checkout-confirm__info").before(`<div class="row justify-content-around mb-2" id="phone"><div class="col-6">Phone :</div><div class="col-4 text-end"><span></span></div><input type="text" name="phone" hidden></div>`);
  return;
}

const initInputPhone = () => {
  $(".wrap-phone").append(`<div class="col col-md-4 col-lg-3 input-group"><div class="input-form__phone row px-2 pt-3 pt-md-0 ps-md-3"><input type="text" class="form-control" aria-label="phone number" aria-describedby="inputGroup-sizing-sm" placeholder="Phone Number" name="phone"><div class="input-feedback ps-1">Please insert phone number</div></div></div>`);
  clearWrapPhone();
  return;
}

const clearWrapPhone = () => {
  $(".wrap-phone").hide({
    done: resetPhoneElement()
  });
}

const resetPhoneElement = () => {
  $(".input-form__phone input[name=phone]").val('');
}

const removeElement = ({ idElement = null, classElement = null }) => {
  if (idElement) $("#" + idElement).remove();
  if (classElement) $("." + classElement).remove();
  return;
}

const clearPayment = () => {
  $(".modal-body #payment span").text('');
  $(".modal-body #payment input[name=payment]").val('');
  $(".modal-body #payment input[name=payment_id]").val('');
  $(".modal-body #amount span").text('');
  $(".modal-body #amount :input").val('');
  $(".modal-body #phone").remove();
  clearItems();
  return;
}

const clearItems = () => {
  clearWrapPhone();
  $(".modal-body #price span").text('');
  $(".modal-body #price input[name=price]").val('');
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

const addRemoveClass = ({ element = null, addClass = null, removeClass = null }) => {
  $(element).removeClass(removeClass);
  $(element).addClass(addClass);
  return;
}