<div class="modal fade" id="detailPaymentModal" tabindex="-1" aria-labelledby="detailPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header box-invoice__header">
        <h5 class="modal-title" id="modalPaymentLabel"></h5>
        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="infoCaution" class="info-caution">
        <div class="modal-body text-center">
          <p class="info-caution__empty-player" hidden></p>
          <p class="info-caution__empty-country" hidden></p>
          <p class="info-caution__empty-payment" hidden></p>
          <p class="info-caution__empty-phone" hidden></p>
          <p class="info-caution__empty-item" hidden></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="button__cancel" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
      <form action="{{ route('payment.transaction') }}" method="post" id="formCheckout">
        @csrf
        <div class="modal-body checkout-confirm">
          <div class="row justify-content-around mb-2 mb-2 d-none" id="checksum">
            <input type="text" name="hash_checksum" hidden>
          </div>
          <div class="row justify-content-around mb-2 mb-2" id="nameGame">
            <div class="col-6">
              Game :
            </div>
            <div class="col-4 text-end">
              <span></span>
            </div>
            <input type="text" name="game_id" hidden>
          </div>
          <div class="row justify-content-around mb-2" id="playerId">
            <div class="col-6">
              Player ID :
            </div>
            <div class="col-4 text-end">
              <span></span>
            </div>
            <input type="text" name="player_id" hidden>
          </div>
          <div class="row justify-content-around mb-2" id="playerName">
            <div class="col-6">
              Username :
            </div>
            <div class="col-4 text-end">
              <span></span>
            </div>
            <input type="text" name="username" hidden>
          </div>
          <div class="row justify-content-around mb-2" id="emailInpt">
            <div class="col-6">
              Email :
            </div>
            <div class="col-4 text-end">
              <span></span>
            </div>
            <input type="text" name="email" hidden>
          </div>
          <div class="row justify-content-around mb-2" id="amount">
            <div class="col-6">
              Amount :
            </div>
            <div class="col-4 text-end">
              <span></span>
            </div>
            <input type="text" name="amount" hidden>
          </div>
          <div class="row justify-content-around mb-2" id="price">
            <div class="col-6">
              Price :
            </div>
            <div class="col-4 text-end">
              <span></span>
            </div>
            <input type="text" name="price" hidden>
            <input type="text" name="price_id" id="priceId" hidden>
          </div>
          <div class="row justify-content-around mb-2" id="payment">
            <div class="col-6">
              Method Payment :
            </div>
            <div class="col-4 text-end">
              <span></span>
            </div>
            <input type="text" name="payment" hidden>
            <input type="text" name="payment_id" id="payment" hidden>
          </div>
          <div class="row checkout-confirm__info mt-2">
            <div class="col-12">
              <p>*Pastikan ID Player dan item yang dipilih sudah sesuai.</p>
            </div>
          </div>
          <div class="row">
            <div class="captcha col-12">
              <div class="row">
                <div class="col-6">
                  <div class="row">
                    <div class="col-6 pe-0">
                      <span>{!! captcha_img() !!}</span>
                    </div>
                    <div class="col-4 ps-0">
                      <button type="button" class="btn btn-danger" class="reload" id="reload">
                        &#x21bb;
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                </div>
              </div>
            </div>            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="button__cancel" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="button__primary">Continue</button>
        </div>
      </form>
    </div>
  </div>
</div>