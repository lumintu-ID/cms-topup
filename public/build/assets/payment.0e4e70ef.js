$(document).ready(function(){const d=window.location.origin,o=JSON.parse(document.getElementsByClassName("games-info__body")[0].dataset.game);JSON.parse(document.getElementsByClassName("payment-list")[0].dataset.paymentcategory);const e=JSON.parse(document.getElementsByClassName("player-input")[0].dataset.infotext);let l;$(".input-feedback.input-id-player").text(e.infoTextInput.idPlayer),$(".input-feedback.input-country").text(e.infoTextInput.country),$(".modal-body #nameGame span").text(o.title),$(".modal-body #nameGame :input").val(o.id),$("#formCheckout").hide();const p=n=>{$("#modalPaymentLabel").text(),$("#modalPaymentLabel").text(n)},m=({showElement:n=null,hideElement:a=null})=>{$(n).show(),$(a).hide()};m({showElement:"#formCheckout"}),$("#btnConfirm").prop("disabled",!1),$("#btnConfirm").click(function(){if(p(e.titleModal.alertInfo),$("#idPlayer").val()==""){m({showElement:"#infoCaution",hideElement:"#formCheckout"}),$(".info-caution__empty-country, .info-caution__empty-payment, .info-caution__empty-item").attr("hidden",!0),$(".info-caution__empty-player").removeAttr("hidden").text(e.alert.idPlayer);return}if($(".input-form__country .form-select").val()==""||!$(".input-form__country .form-select").val()){m({showElement:"#infoCaution",hideElement:"#formCheckout"}),$(".info-caution__empty-player, .info-caution__empty-payment, .info-caution__empty-item").attr("hidden",!0),$(".info-caution__empty-country").removeAttr("hidden").text(e.alert.country);return}if($(".modal-body #payment input[name=payment]").val()==""||$(".payment-list").children().length<=0){m({showElement:"#infoCaution",hideElement:"#formCheckout"}),$(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-item").attr("hidden",!0),$(".info-caution__empty-payment").removeAttr("hidden").text(e.alert.payment);return}if($(".modal-body #price input[name=price]").val()==""){m({showElement:"#infoCaution",hideElement:"#formCheckout"}),$(".info-caution__empty-player, .info-caution__empty-country, .info-caution__empty-payment").attr("hidden",!0),$(".info-caution__empty-item").removeAttr("hidden").text(e.alert.item);return}p(e.titleModal.purchase),m({showElement:"#formCheckout",hideElement:"#infoCaution"})}),$(".total-payment__nominal").text(0),$("#idGameInpt").val(o.id),$("#btnClearId").hide(),$("#btnCheckId").click(async function(n){if(!$("#idPlayer").val()){$(".input-feedback.input-id-player").addClass("invalid"),$(".input-feedback.input-id-player").text(e.infoTextInput.warning);return}await fetch(`${d}/api/v1/player`).then(a=>{if(a.status===404){$(".input-feedback.input-id-player").removeClass("valid invalid"),$(".input-feedback.input-id-player").addClass("invalid"),$(".input-feedback.input-id-player").text(e.infoTextInput.playerNotFound),$("#formCheckout").children("div").last().remove(),$("#formCheckout").append('<div class="info-user">Data user tidak tersedia, silahkan coba kembali</div>');return}return a.json()}).then(a=>{$(this).hide(),l=a.data,$(".input-feedback.input-id-player").removeClass("valid invalid"),$(".input-feedback.input-id-player").addClass("valid"),$(".input-feedback.input-id-player").text(`Player name: ${l.username}`),$("#btnClearId").show(),$(".modal-body #playerId :input").val($("#idPlayer").val()),$(".modal-body #playerId span").text($("#idPlayer").val()),$(".modal-body #playerName :input").val(l.username),$(".modal-body #playerName span").text(l.username),$(".modal-body #emailInpt :input").val(l.email),$(".modal-body #emailInpt span").text(l.email),$("#idPlayer").prop("disabled",!0)}).catch(a=>{console.log(a)})}),$("#btnClearId").click(function(){$(this).hide(),$("#btnCheckId").show(),$("#idPlayer").prop("disabled",!1),$("#idPlayer").val(""),$(".input-feedback.input-id-player").removeClass("valid invalid"),$(".input-feedback.input-id-player").text(e.infoTextInput.idPlayer),$("#formCheckout").children(".info-user").remove(),s()}),$(".input-form__country .form-select").change(async function(){$("#paymentLoader, #paymentLoader .spinner-border").addClass("mt-5").show(),$(".payment-list").empty(),$(".price-list").empty(),u(),this.value&&await fetch(`${d}/api/v1/payment?country=${this.value}&game_id=${o.id}`).then(n=>{if(n.status===404){r({element:".payment-list",addClass:"justify-content-center",removeClass:"justify-content-start"}),$(".payment-list").append(e.noPayment);return}return n.json()}).then(n=>{const a=n.data;r({element:".payment-list",addClass:"justify-content-start",removeClass:"justify-content-center"}),a.map(i=>{$(".payment-list").append(`
              <div class="col">
                <div class="payment-list__items" data-payment="${i.payment.payment_id}">
                  <input type="radio" id="${i.payment.payment_id}" name="payment-id" value="${i.payment.payment_id}">
                  <img src="${i.payment.logo_channel}" title="${i.payment.name_channel}" alt="${i.payment.name_channel}" class="ps-2">
                </div>
              </div>
            `)}),$("#paymentLoader, #paymentLoader .spinner-border").removeClass("mt-5").hide(),$("#formCheckout").hide(),$("#infoCaution").show(),$(".payment-list__items").click(function(){c(),$(this).children().prop("checked",!0);const{payment:i,price:y}=a.find(({payment:t})=>t.payment_id==this.dataset.payment);$(".price-list").empty(),$(".modal-body #payment span").text(i.name_channel),$(".modal-body #payment input[name=payment]").val(i.name_channel),$(".modal-body #payment input[name=payment_id]").val(i.payment_id),$("#formCheckout").hide(),$("#infoCaution").show(),y.map(t=>{$(".price-list").append(`
                <div class="col">
                  <div class="amount-price__wrap d-flex justify-content-between p-2" data-priceid="${t.price_id}">
                    <div class="amount-price__name-item">
                      <input type="radio" id="${t.price_id}" name="price-id" value="${t.price_id}">
                      ${t.amount} ${t.name}
                    </div>
                    <div class="amount-price__price" id="price">${t.price}</div>
                  </div>
                </div>`)}),$(".amount-price__wrap").click(function(){$(this).children(".amount-price__name-item").children().prop("checked",!0);const t=parseInt($(this).children(".amount-price__price").text());$(".total-payment__nominal").text(t),$(".modal-body #price :input").val(t),$(".modal-body #price span").text(t),$(".modal-body #amount input[name=amount]").val($(this).children(".amount-price__name-item").text()),$(".modal-body #amount span").text($(this).children(".amount-price__name-item").text()),$(".modal-body #priceId").val($(this).children(".amount-price__name-item").children("input").val()),$("#formCheckout").show(),$("#infoCaution").hide()})})}).catch(n=>{$("#paymentLoader, #paymentLoader .spinner-border").removeClass("mt-5").hide(),$(".payment-list").empty(),$(".payment-list").addClass("justify-content-center"),$(".payment-list").append(e.noPayment),$(".price-list").empty()})})});const u=()=>{$(".modal-body #payment span").text(""),$(".modal-body #payment input[name=payment]").val(""),$(".modal-body #payment input[name=payment_id]").val(""),$(".modal-body #amount span").text(""),$(".modal-body #amount :input").val(""),c()},c=()=>{$(".modal-body #price span").text(""),$(".modal-body #price input[name=price]").val(""),$(".total-payment__nominal").text("0")},s=()=>{$(".modal-body #playerId :input").val($("#idPlayer").val()),$(".modal-body #playerId span").text($("#idPlayer").val()),$(".modal-body #playerName :input").val(""),$(".modal-body #playerName span").text("")},r=({element:d=null,addClass:o=null,removeClass:e=null})=>{$(d).removeClass(e),$(d).addClass(o)};
