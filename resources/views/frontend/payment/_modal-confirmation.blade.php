<div class="modal fade" id="detailPaymentModal" tabindex="-1" aria-labelledby="detailPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header box-invoice__header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Pembelian</h5>
        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="infoCaution" class="info-caution">
        <div class="modal-body text-center">
          <p class="info-caution__empty-all">
            Silahkan masukan id player dan pilih jumlah item.
          </p>
          <p class="info-caution__empty-player" hidden>
            Silahkan masukan id player.
          </p>
          <p class="info-caution__empty-item" hidden>
            Silahkan pilih jumlah item.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="button__cancel" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
      <form action="{{ route('payment.transaction') }}" method="post" id="formCheckout">
        @csrf
        <div class="modal-body checkout-confirm">
          <div class="row" id="nameGame">
            <div>
              Game: <span></span>
            </div>
            <input type="text" name="game_id" hidden>
          </div>
          <div class="row" id="playerId">
            <div>
              Player ID: <span></span>
            </div>
            <input type="text" name="player_id" hidden>
          </div>
          <div class="row" id="playerName">
            <div>
              Username: <span></span>
            </div>
            <input type="text" name="username" hidden>
          </div>
          <div class="row" id="emailInpt">
            <div>
              Email: <span></span> 
            </div> 
            <input type="text" name="email" hidden>
          </div>
          <div class="row" id="amount">
            <div>
              Amount: <span></span>
            </div>
            <input type="text" name="amount" hidden>
          </div>
          <div class="row" id="price">
            <div>
              Price: <span></span>
            </div>
            <input type="text" name="price" hidden>
            <input type="text" name="price_id" id="priceId" hidden>
          </div>
          <div class="row" id="payment">
            <div>
              Method Payment: <span></span>
            </div>
            <input type="text" name="payment" hidden>
            <input type="text" name="payment_id" hidden>
          </div>
          <div class="row checkout-confirm__info mt-2">
            <p>*Pastikan ID Player dan item yang dipilih sudah sesuai.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="button__cancel" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="button__primary">Lanjutkan</button>
        </div>
      </form>
    </div>
  </div>
</div>