<div class="row row-cols-1 row-cols-sm-2 gy-1 pb-sm-1">
  <div class="col-12 col-md-8 p-0">
    <div class="row align-items-top align-items-sm-center align-items-md-start">
      <div class="col-4 col-sm-5 col-md-4 col-xl-3">
        <div class="games-info__image">
          <img src="{{ asset('cover/'.$dataGame->cover) }}" class="img-fluid" alt="icon-game">
        </div>
      </div>
      <div class="col-8 col-sm-7 col-md-8 ps-0 pt-2 pt-md-3">
        <div class="games-info__body" data-game="{{ $dataGame }}">
          <h2 class="games-info__title">{{ $dataGame->title }} </h2>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4 d-sm-flex align-items-sm-end align-items-md-center justify-content-md-end p-0">
    <div class="games-info__button">
      <div class="row row-cols-2 row-cols-sm-1 gy-2 py-2">
        <div class="d-flex justify-content-end">
         <a href="#" class="button__primary">
            Portal Info
          </a>
        </div>
        <div class="col d-flex justify-content-end">
          <a href="#" class="button__primary">
            GAMES
          </a>
        </div>
      </div>
    </div>
  </div>     
</div>