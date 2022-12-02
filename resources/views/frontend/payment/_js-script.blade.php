<script>
  let urlCheckout, dataPayment, priceList, country = null;
  
  $(document).ready(function(){
    const baseUrl = window.location.origin;
    const idGame = document.getElementsByClassName('games-info__body')[0].dataset.id;

    if(!country) $(".payment-list").append('Silahkan pilih negara');

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
          console.log(data);
          dataPayment = data.data;
          
          dataPayment.forEach((data) => {
            priceList = data.price;
            $(".payment-list").empty();
            $(".payment-list").append(`
              <div class="col">
                <div class="payment-list__items" data-payment="${data.payment.payment_id}">
                  <img src="${data.payment.logo_channel}" alt="${data.payment.logo_channel}">
                </div>
              </div>
            `)
          });
          $(".payment-list__items").click(function() {
            console.log(priceList);
            $(".price-list").empty();
            priceList.forEach((data) => {
              $(".price-list").append(`
                <div class="col">
                  <div class="amount-price__wrap d-flex justify-content-between p-2">
                    <div class="amount-price__name-item">Stone</div>
                    <div class="amount-price__price">${data.price}</div>
                  </div>
                </div>`
              )
            });
            console.log(this.dataset.payment);
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

    
    $("button").click(function() {
      console.log(dataPayment);
    });
  });
 
</script>