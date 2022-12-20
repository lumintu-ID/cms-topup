<div class="row row-cols-1 row-cols-md-2">
  <div class="col-12 col-lg-4">
    <div class="input-form__player input-group input-group mb-3">
      <input type="text" class="form-control" aria-label="Id player input" aria-describedby="inputGroup-sizing-sm" placeholder="ID User" name="player-id" id="idPlayer">
      <button type="button" class="input-group-text" id="btnCheckId">Check</button>
      <button type="button" class="input-group-text" id="btnClearId">Clear</button>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="input-form__country input-group mb-3">
      <select class="form-select" aria-label="Select countries">
        <option value='' selected>Negara</option>
        <option disabled></option>
        @foreach ($countries as $data)
        <option value="{{ $data['id'] }}">{{ $data['country'] }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>
