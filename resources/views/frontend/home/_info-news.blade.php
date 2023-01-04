<div class="col">
  <div class="row row-cols-2 row-cols-sm-3 g-3 g-lg-5">
    @foreach ($articles['articles'] as $item)
    <div class="col">
      <div class="row align-items-center align-items-md-start">
        <div class="promo-info-news">
          <div class="promo-info-news__thumbnail">
            <img src="{{ $item['image'] }}" alt="image thumbnail">
          </div>
          <div class="promo-info-news__body">
            <div class="promo-info-news__title pt-md-1">
              <a href="{{ $item['url'] }}">
                {!! $item['title'] !!}
              </a>
            </div>
            <div class="promo-info-news__descriptions">
              {{ $item['description'] }}
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>