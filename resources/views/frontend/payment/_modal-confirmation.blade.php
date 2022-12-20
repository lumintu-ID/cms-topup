<div class="modal fade" id="detailPaymentModal" tabindex="-1" aria-labelledby="detailPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Pembelian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('payment.transaction') }}" method="post" id="formCheckout">
        @csrf
        <div class="modal-body">
          *Pastikan username dan ID sudah benar.
          <div class="row">
            Game ID <input type="text" name="game_id" id="idGameInpt" >
          </div>
          <div class="row">
            Player ID <input type="text" name="player_id" id="idPlayer" >
          </div>
          <div class="row">
            Username <input type="text" name="username" id="playerName" >
          </div>
          <div class="row">
            Email <input type="text" name="email" id="emailInpt" >
          </div>
          <div class="row">
            Amount: <input type="text" name="amount" id="amountInpt">
          </div>
          <div class="row">
            Price <input type="text" name="price" id="price" >
            ID Price <input type="text" name="price_id" id="priceId" >
          </div>
          <div class="row">
            Method Payment <input type="text" name="payment" id="payment" >
            ID Payment <input type="text" name="payment_id" id="paymentId" >
          </div>
          <div class="row">
            Total Payment <input type="text" name="total" id="totalPayment" >
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Lanjutkan</button>
        </div>
      </form>
    </div>
  </div>
</div>