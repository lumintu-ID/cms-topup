<div class="row row-cols-1 row-cols-md-2">
  <div class="col-12 col-lg-4">
    <div class="input-form__player input-group input-group mb-3">
      <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="ID User">
      <button class="input-group-text" id="btnCheckId">Check</button>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="input-form__country input-group mb-3">
      <select class="form-select" aria-label="Select countries">
        <option value='' selected>Negara</option>
        <option disabled></option>
        @foreach ($countries as $data)
        <option value="{{ $data->country_id }}">{{ $data->country }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>
