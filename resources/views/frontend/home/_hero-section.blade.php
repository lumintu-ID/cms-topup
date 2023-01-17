@section('css-utilities')
  <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
@endsection
<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    @foreach ($banners as $banner)
      <div class="swiper-slide">
        <img src="{{ asset('banner/'.$banner->name) }}" alt="{{ $banner->name }}" class="img-fluid">
      </div>
    @endforeach
  </div>
  <div class="swiper-button-next">
    <img src="{{ asset('assets/website/images/banner/right.png') }}" alt="arrow right">
  </div>
  <div class="swiper-button-prev">
    <img src="{{ asset('assets/website/images/banner/left.png') }}" alt="arrow left">
  </div>
  <div class="swiper-pagination"></div>
</div>

@section('js-utilities')
  @include('frontend.home._hero-section-js')
@endsection