@foreach ($games as $game)
  <div class="col-6 col-sm-3 col-md-4 col-xl-3 text-center px-lg-5">
    <a href="{{ route('payment', $game->slug_game) }}">
      <div class="games-card">
        <div class="games-card__body">
          <img src="{{ asset('assets/website/images/games/fol-games-image.png') }}" alt="fight of legends" class="img-fluid">
        </div>
        <div class="games-card__footer">
          <div class="games-card__footer-text">
            {{ $game->game_title }}
          </div>
        </div>
      </div>
    </a>
  </div>
@endforeach
