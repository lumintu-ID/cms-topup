@extends('frontend.layouts.app')
@section('content')
<section class="section-invoice my-4 py-4">
  <div class="row">
    <div class="col-12 col-sm-4">
      <form action="{{ route('payment') }}" method="get">
        <div class="input-form__player input-group">
          <input type="text" class="form-control" aria-label="invoice input" aria-describedby="inputGroup-sizing-sm" placeholder="No Invoice" name="id">
          <button type="submit" class="input-group-text">Check</button>
        </div>
      </form>

    </div>
  </div>
</section>


@endsection

