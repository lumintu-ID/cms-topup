<script>
  let urlCheckout; 
  let dataPayment; 
  let country = null;
  let ammount = 0;
  let datetrx = new Date().toISOString().slice(0, 19) + '+07';
  let nameUser = 'Lupiz';
  // let phoneUser = '08777535648447';
  let phoneUser = '081240157378';
  let channelId;
  let currency = 'IDR';
  let returnUrl = 'http://127.0.0.1:8000/';
  const gocpayHaskey = 'jqji815m748z0ql560982426ca0j70qk02411d2no6u94qgdf58js2jn596s99si';

  // let datetrx = new Date();
  const email = 'testuserid@gmail.com';
  console.log(datetrx);
  
  $(document).ready(function(){

    
    // console.log(merchantId);

    const baseUrl = window.location.origin;
    const idGame = document.getElementsByClassName('games-info__body')[0].dataset.id;

    if(!country) $(".payment-list").append('Silahkan pilih negara');

    $(".total-payment__nominal").text(ammount);

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
                  <img src="${data.payment.logo_channel}" title="${data.payment.name_channel}" alt="${data.payment.name_channel}" onerror="this.src='${baseUrl}/cover/1669259128_fol-games-image.png'">
                  ${data.payment.name_channel}
                </div>
              </div>
            `);
          });
          $(".payment-list__items").click(function() {
            $(this).children().prop("checked", true);
            const priceList = dataPayment.find(({payment}) => payment.payment_id == this.dataset.payment );
            channelId = priceList.payment.channel_id;
            $('input[name="channelId"]').val(channelId);
            urlCheckout = priceList.payment.url;
            $('form').attr('action', urlCheckout);

            // console.log(priceList)
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
              $(this).children(".amount-price").prop("checked", true);
              ammount = parseInt($(this).children('.amount-price__price').text());
              $('input[name="amount"]').val(ammount);
              $(".total-payment__nominal").text(ammount);

              const plainSign = merchantId[0].value + trxId[0].value + datetrx + channelId + ammount + currency + gocpayHaskey;
              // console.log(plainSign);
              $('input[name="sign"]').val(plainSign);
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

    
    const merchantId = $('input[name="merchantId"]').val('Esp5373790');
    const trxId = $('input[name="trxId"]').val(Math.random().toString(8).slice(2));
    $('input[name="trxDateTime"]').val(datetrx);
    $('input[name="email"]').val(email);
    $('input[name="nameUser"]').val(nameUser);
    $('input[name="phone"]').val(phoneUser);
    $('input[name="currency"]').val(currency);
    $('input[name="returnUrl"]').val(returnUrl);
    const userId = $('input[name="userId"]').val(Math.random().toString(8).slice(2));
    

    // let sign = merchantId+trxId+datetrx
    // channelId = $('input[name="channelId"]').val();
    // console.log(channelId);
    // const sign = $('input[name="sign"]').val();
   
  
    $(".button__primary").click(function() {
      // const form = $('form').attr('action');
      const form = 'from';
      console.log(form);
    });
  });
 
</script>