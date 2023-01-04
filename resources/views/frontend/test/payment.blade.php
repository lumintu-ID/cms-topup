
@extends('frontend.test.index')

@section('content')
<section class="section-games-info">
  <div class="row row-cols-1 row-cols-sm-2 gy-1 pb-sm-1">
    @include('frontend.test._info-game')
  </div>
</section>
<section class="section-player">
  @include('frontend.test._player-input')
</section>
<section class="section-total-payment">
  @include('frontend.test._total-payment')
</section>

@endsection