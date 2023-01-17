@extends('frontend.layouts.app')

@section('content')
<section class="section-games mt-4">
  <div class="row">
    <div class="label-section">List Game</div>
  </div>
  <div class="row gy-4 gy-md-5 py-3 py-lg-4">
    {{-- @for ($i = 0; $i < $count = 5; $i++) --}}
      @include('frontend._component.card-games')
    {{-- @endfor --}}
  </div>
  <div class="row justify-content-center mt-3 py-5 px-3">
    <a href="#" class="button__primary">
      Load more
    </a>
  </div>
</section>
@endsection