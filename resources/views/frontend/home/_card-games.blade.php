@foreach ($games as $game)
<div class="col-6 col-md-3 col-lg-4 col-xl-3 text-center px-lg-5">
  <a href="{{ route('payment', $game->slug_game) }}">
    <div class="games__card">
      <div class="games-card__body">
        <img src="{{ asset('assets/website/images/games/fol-games-image.png') }}" alt="fight of legends">
      </div>
      <div class="games-card__footer">
        <div class="games-card-footer__text">
          Fight of Legends
        </div>
      </div>
    </div>
  </a>
</div>
@endforeach
