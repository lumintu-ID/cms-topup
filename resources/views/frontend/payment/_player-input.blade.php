<div class="player-input row row-cols-1 row-cols-md-2" data-infotext="{{ $textAttribute }}">
  <div class="col-12 col-lg-4" >
    <div class="row">
      <div class="input-form__player input-group">
        <input type="text" class="form-control" aria-label="Id player input" aria-describedby="inputGroup-sizing-sm" placeholder="ID Player" name="player-id" id="idPlayer">
        <button type="button" class="input-group-text" id="btnCheckId">Check</button>
        <button type="button" class="input-group-text" id="btnClearId">Clear</button>
      </div>
      <div class="input-feedback input-id-player"></div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="row">
      <div class="input-form__country input-group">
        <select class="form-select" aria-label="Select countries">
          <option disabled selected>Country</option>
          @foreach ($countries as $data)
          <option value="{{ $data['id'] }}">{{ $data['country'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="input-feedback input-country"></div>
    </div>
  </div>
</div>
